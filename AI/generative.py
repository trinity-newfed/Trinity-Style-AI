import os
import sys
import time
import gc
import io
import json
import base64
import redis
from pathlib import Path

os.environ["HIP_VISIBLE_DEVICES"] = "1"
os.environ["HSA_OVERRIDE_GFX_VERSION"] = "11.0.0"
os.environ["PYTORCH_ROCM_ARCH"] = "gfx1100"

os.environ["PYTORCH_HIP_ALLOC_CONF"] = "expandable_segments:True"
os.environ["TORCH_BLAS_PREFER_HIPBLASLT"] = "0"
os.environ["USE_HIPBLASLT"] = "0"
os.environ["HSA_ENABLE_SDMA"] = "0"
os.environ["ROCM_PATH"] = "/opt/rocm"

import torch
import cv2
import numpy as np
from PIL import Image, ImageFilter

import torch.backends.cuda
torch.backends.cuda.enable_flash_sdp(False)
torch.backends.cuda.enable_mem_efficient_sdp(True)
torch.backends.cuda.enable_math_sdp(True)

from transformers import SegformerImageProcessor, SegformerForSemanticSegmentation
from diffusers import (
    ControlNetModel,
    StableDiffusionControlNetInpaintPipeline,
    DPMSolverMultistepScheduler
)

sys.stdout.reconfigure(line_buffering=True)

device = "cuda" if torch.cuda.is_available() else "cpu"
dtype = torch.float16 if device == "cuda" else torch.float32

if device == "cuda":
    gpu_name = torch.cuda.get_device_name(0)
    print(f"[*] Target hardware detected: {device.upper()} - {gpu_name} ({dtype})")
else:
    print(f"[*] Target hardware detected: {device.upper()} ({dtype})")

is_nvidia = torch.cuda.is_available() and "nvidia" in torch.cuda.get_device_name(0).lower()
is_amd = torch.cuda.is_available() and (("amd" in torch.cuda.get_device_name(0).lower()) or (torch.version.hip is not None))

print(f"[*] Optimizing pipeline for: {'NVIDIA' if is_nvidia else 'AMD' if is_amd else 'CPU'}")

# Dynamic IP Mapping
DEFAULT_REDIS_HOST = "127.0.0.1" if is_amd else "trinity_redis_ai"
DEFAULT_REDIS_PORT = 6380 if is_amd else 6379
DEFAULT_DB_HOST = "127.0.0.1" if is_amd else "trinity_db"

REDIS_HOST = os.getenv("REDIS_AI_HOST", DEFAULT_REDIS_HOST)
REDIS_PORT = int(os.getenv("REDIS_AI_PORT", os.getenv("REDIS_PORT", DEFAULT_REDIS_PORT)))
REDIS_PASSWORD = os.getenv("REDIS_PASSWORD", None)
PENDING_HASH = "ai_pending_tasks"

def get_redis_client():
    print(f"[*] Connecting to Redis at {REDIS_HOST}:{REDIS_PORT}...")
    try:
        pool = redis.ConnectionPool(
            host=REDIS_HOST, port=REDIS_PORT, password=REDIS_PASSWORD,
            db=0, decode_responses=True, socket_keepalive=True
        )
        client = redis.Redis(connection_pool=pool)
        client.ping()
        print(f"[✓] Redis Connect Successfully")
        return client
    except Exception as e:
        print(f"[X] Critical: Failed to connect to Redis Pool: {e}")
        raise e

def update_redis_status(client, task_id, progress, status):
    try:
        client.hset(f"task:{task_id}", mapping={"progress": progress, "status": status})
    except Exception as e:
        print(f"[!] Cannot update Redis task progress {task_id}: {e}")

# DB Config
DB_CONFIG = {
    "host": os.getenv("DB_HOST", DEFAULT_DB_HOST),
    "user": os.getenv("DB_USER", "root"),
    "password": os.getenv("DB_PASSWORD", "root_password"),
    "database": os.getenv("DB_NAME", "TF_Database"),
    "connection_timeout": 10
}

def resize_with_padding(img: Image.Image, target_size=(512, 768)):
    w, h = img.size
    scale = min(target_size[0] / w, target_size[1] / h)
    new_w, new_h = int(w * scale), int(h * scale)
    img_resized = img.resize((new_w, new_h), Image.LANCZOS)
    new_img = Image.new("RGB", target_size, (0, 0, 0))
    paste_x = (target_size[0] - new_w) // 2
    paste_y = (target_size[1] - new_h) // 2
    new_img.paste(img_resized, (paste_x, paste_y))
    return new_img

def get_raw_segformer_mask(person_img: Image.Image, seg_processor, seg_model):
    orig_w, orig_h = person_img.size
    inputs = seg_processor(images=person_img, return_tensors="pt")
    inputs = {k: v.to(device) for k, v in inputs.items()}
    
    with torch.no_grad():
        outputs = seg_model(**inputs)
        
    logits = outputs.logits
    upsampled_logits = torch.nn.functional.interpolate(
        logits, size=(orig_h, orig_w), mode="bilinear", align_corners=False
    )
    pred = upsampled_logits.argmax(dim=1)[0].cpu().numpy()
    
    raw_mask = np.zeros_like(pred, dtype=np.uint8)
    raw_mask[pred == 4] = 255
    return raw_mask

def process_mask_and_canny(person_img: Image.Image, raw_mask_np: np.ndarray):
    person_np = np.array(person_img)
    
    contours, _ = cv2.findContours(raw_mask_np, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    filled_mask = np.zeros_like(raw_mask_np)
    cv2.drawContours(filled_mask, contours, -1, 255, thickness=cv2.FILLED)
    
    kernel_dilate = np.ones((13, 13), np.uint8)
    expanded_mask = cv2.dilate(filled_mask, kernel_dilate, iterations=1)
    
    expanded_mask = cv2.GaussianBlur(expanded_mask, (5, 5), 0)
    _, final_mask_np = cv2.threshold(expanded_mask, 127, 255, cv2.THRESH_BINARY)
    
    edges = cv2.Canny(person_np, 50, 150)
    
    kernel_erase = np.ones((17, 17), np.uint8)
    mask_for_erase = cv2.dilate(final_mask_np, kernel_erase, iterations=1)
    
    if edges.shape == mask_for_erase.shape:
        edges[mask_for_erase == 255] = 0
    else:
        mask_for_erase = cv2.resize(mask_for_erase, (edges.shape[1], edges.shape[0]), interpolation=cv2.INTER_NEAREST)
        edges[mask_for_erase == 255] = 0
    
    final_mask = Image.fromarray(final_mask_np)
    canny_image = Image.fromarray(np.stack([edges] * 3, axis=-1))
    
    return final_mask, canny_image

def restore_face_and_upscale(original_img: Image.Image, generated_img: Image.Image, mask_img: Image.Image, target_size=(1024, 1536)):
    orig_upscaled = resize_with_padding(original_img, target_size)
    
    gen_upscaled = generated_img.resize(target_size, Image.LANCZOS)
    gen_upscaled = gen_upscaled.filter(ImageFilter.UnsharpMask(radius=2, percent=150, threshold=3))
    
    mask_upscaled = mask_img.resize(target_size, Image.LANCZOS).convert("L")
    mask_blurred = mask_upscaled.filter(ImageFilter.GaussianBlur(radius=7))
    
    final_img = Image.composite(gen_upscaled, orig_upscaled, mask_blurred)
    return final_img

def process_task(r_client, task_data):
    task_id = task_data.get("task_id")
    user_id = task_data.get("user_id", "default_user")
    cloth_path_raw = task_data.get("product_img")
    base64_str = task_data.get("image_base64")

    print(f"\n[+] Processing High-Fidelity Task ID: {task_id}")
    update_redis_status(r_client, task_id, 10, "processing")

    if not base64_str or not cloth_path_raw:
        print("[X] Missing data base64 from Redis!")
        update_redis_status(r_client, task_id, 0, "failed")
        return

    cloth_path = os.path.join("picture-uploads", cloth_path_raw) if not cloth_path_raw.startswith("picture-uploads") else cloth_path_raw
    
    script_dir = Path(__file__).resolve().parent
    project_root = script_dir.parent if script_dir.name == "AI" else script_dir
    
    style_image_path = project_root / cloth_path
    if not style_image_path.exists():
        print(f"[X] Couldn't locate dir path: {style_image_path}")
        update_redis_status(r_client, task_id, 0, "failed")
        return

    output_dir = script_dir / "static" / str(user_id)
    output_dir.mkdir(parents=True, exist_ok=True)

    try:
        if "," in base64_str:
            base64_str = base64_str.split(",")[1]
        
        person_bytes = base64.b64decode(base64_str)
        person_img = Image.open(io.BytesIO(person_bytes)).convert("RGB")
    except Exception as e:
        print(f"[X] Failed to resolve image from Redis: {e}")
        update_redis_status(r_client, task_id, 0, "failed")
        return

    try:
        cloth_img = Image.open(style_image_path).convert("RGB")
    except Exception as e:
        print(f"[X] Failed to read at {style_image_path}: {e}")
        update_redis_status(r_client, task_id, 0, "failed")
        return

    person_resized = resize_with_padding(person_img, target_size=(512, 768))
    cloth_resized = resize_with_padding(cloth_img, target_size=(512, 768))

def load_ai_models():
    global seg_processor, seg_model, controlnet, pipe

    seg_model_name = "sayeed99/segformer_b3_clothes"
    seg_processor = SegformerImageProcessor.from_pretrained(seg_model_name)
    seg_model = SegformerForSemanticSegmentation.from_pretrained(seg_model_name).to(device).eval()

    controlnet = ControlNetModel.from_pretrained(
        "lllyasviel/control_v11p_sd15_canny",
        torch_dtype=dtype
    )

    pipe = StableDiffusionControlNetInpaintPipeline.from_pretrained(
        "runwayml/stable-diffusion-inpainting",
        controlnet=controlnet,
        torch_dtype=dtype
    )

    pipe.scheduler = DPMSolverMultistepScheduler.from_config(
        pipe.scheduler.config,
        algorithm_type="dpmsolver++",
        use_karras_sigmas=True
    )

    pipe.load_ip_adapter(
        "h94/IP-Adapter",
        subfolder="models",
        weight_name="ip-adapter-plus_sd15.bin"
    )
    pipe.set_ip_adapter_scale(0.85)

    if device == "cuda":
        pipe.to("cuda")
        pipe.enable_vae_slicing()

    pipe.safety_checker = None

    print("[✓] Pipeline Loaded!")

    prompt = "A highly realistic photo of the same person wearing the clothing from the reference image, natural lighting, realistic fabric texture, symmetrical collar, centered zipper, perfect anatomy, high quality"
    negative_prompt = "color change, faded colors, blurry, distorted body, extra arms, halo, white aura, glowing background, misaligned zipper, messy edges"

    seed = torch.randint(0, 1_000_000, (1,)).item()
    generator = torch.Generator(device=device).manual_seed(seed)

    print(f"\n[*]Seed: {seed})...")
    start_time = time.time()

    with torch.inference_mode():
        result = pipe(
            prompt=prompt,
            negative_prompt=negative_prompt,
            image=person_resized,
            mask_image=final_mask,
            control_image=canny_image,
            ip_adapter_image=cloth_resized, 
            num_inference_steps=30,
            strength=1.0,
            guidance_scale=7.5,
            controlnet_conditioning_scale=0.6,
            generator=generator
        )

    final_generated_image = result.images[0]
    perfect_upscaled_image = restore_face_and_upscale(
        original_img=person_img, 
        generated_img=final_generated_image, 
        mask_img=final_mask, 
        target_size=(1024, 1536)
    )

    output_path = output_dir / f"final_result_optimized_{seed}.png"
    perfect_upscaled_image.save(output_path)

    update_redis_status(r_client, task_id, 100, "success")
    print("==================================================")
    print(f"[✓] Done ({time.time() - start_time:.2f}s)!")
    print(f"[✓] Path: {output_path}")
    print("==================================================")

def main():
    print(f"\n[*] AI CORE ENGINE ONLINE - PRE-LOADING MODELS...")
    
    try:
        load_ai_models()
    except Exception as e:
        print(f"[X] Fatal error with AI pipeline: {e}")
        return

    try: 
        r = get_redis_client()
    except Exception as e: 
        print(f"[X] Couldn't connect to Redis.")
        return

    while True:
        try:
            tasks = r.hgetall(PENDING_HASH)
            if not tasks:
                time.sleep(1)
                continue
            for task_id, raw_payload in tasks.items():
                if r.hdel(PENDING_HASH, task_id):
                    try:
                        task_data = json.loads(raw_payload)
                        process_task(r, task_data)
                    except Exception as inner_err:
                        print(f"[X] Task error {task_id}: {inner_err}")
                        update_redis_status(r, task_id, 0, "failed")
        except (redis.ConnectionError, redis.TimeoutError):
            print("[!] Try again after 5s...")
            time.sleep(5)
            try: 
                r = get_redis_client()
            except: 
                pass
        except KeyboardInterrupt:
            print("\n[*] Stopping AI Engine.")
            break

if __name__ == "__main__":
    main()