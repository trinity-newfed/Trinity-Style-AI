import os
import sys

sys.stdout.reconfigure(line_buffering=True)

import io
import time
import json
import base64
import logging

import torch
import cv2
import numpy as np
from PIL import Image
import mysql.connector
import redis
from transformers import SegformerImageProcessor, SegformerForSemanticSegmentation
from diffusers import ControlNetModel, StableDiffusionControlNetInpaintPipeline
from dotenv import load_dotenv

load_dotenv()
print("[*] Loading environment variables from .env...")

REDIS_HOST = os.getenv("REDIS_AI_HOST", "trinity_redis_ai")
REDIS_PORT = int(os.getenv("REDIS_AI_PORT", 6379))
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

DB_CONFIG = {
    "host": "trinity_db",
    "user": "root",
    "password": "root_password",
    "database": "TF_Database",
    "connection_timeout": 10
}

device = "cuda" if torch.cuda.is_available() else "cpu"
dtype = torch.float16 if device == "cuda" else torch.float32

print(f"[*] Target hardware detected: {device.upper()} ({dtype})")

try:
    seg_model_name = "sayeed99/segformer_b3_clothes"
    seg_processor = SegformerImageProcessor.from_pretrained(seg_model_name)
    seg_model = SegformerForSemanticSegmentation.from_pretrained(seg_model_name)
    seg_model.to(device).eval()
    print("[✓] Segformer Model loaded successfully.")
except Exception as e:
    print(f"[X] Critical: Failed to load Segformer Model: {e}")
    raise e

try:
    controlnet = ControlNetModel.from_pretrained("lllyasviel/control_v11p_sd15_canny", torch_dtype=dtype)
    pipe = StableDiffusionControlNetInpaintPipeline.from_pretrained(
        "runwayml/stable-diffusion-v1-5",
        controlnet=controlnet,
        torch_dtype=dtype
    )
    print("[✓] Stable Diffusion 1.5 & ControlNet Pipeline initialized.")
except Exception as e:
    print(f"[X] Critical: Failed to load SD-ControlNet Pipeline: {e}")
    raise e

# Optimize hardware
is_nvidia = torch.cuda.is_available() and "nvidia" in torch.cuda.get_device_name(0).lower()
is_amd = torch.cuda.is_available() and (("amd" in torch.cuda.get_device_name(0).lower()) or (torch.version.hip is not None))

print(f"[*] Optimizing pipeline for: {'NVIDIA' if is_nvidia else 'AMD' if is_amd else 'CPU'}")

print("[*] Setting up Attention Processor...")
pipe.unet.set_attn_processor(torch.nn.functional.scaled_dot_product_attention)

if is_nvidia:
    pipe.to(device)
    pipe.enable_xformers_memory_efficient_attention()
    

    try:
        print("[*] Linking IP-Adapter-Plus...")
        pipe.load_ip_adapter("h94/IP-Adapter", subfolder="models", weight_name="ip-adapter-plus_sd15.safetensors")
        pipe.set_ip_adapter_scale(1.0)
    except Exception as e:
        print(f"[X] Error during IP-Adapter configuration: {e}")
        raise e

    pipe.enable_model_cpu_offload()
elif is_amd:
    print("[*] Linking IP-Adapter...")
    pipe.load_ip_adapter("h94/IP-Adapter", subfolder="models", weight_name="ip-adapter-plus_sd15.safetensors")
    pipe.set_ip_adapter_scale(1.0)
    # pipe.to(device)
    # pipe.enable_model_cpu_offload()
    # <4GB Vram Optimize, Please Comment This If Your Hardware Vram Is Greater Than 4GB 
    pipe.enable_attention_slicing(slice_size="max")
    pipe.enable_vae_tiling()
    pipe.enable_sequential_cpu_offload(device=device)
else:
    pipe.to("cpu")

pipe.safety_checker = None




def resize_with_padding(img, target_size=(512, 768)):
    w, h = img.size
    scale = min(target_size[0]/w, target_size[1]/h)
    new_w, new_h = int(w*scale), int(h*scale)
    img_resized = img.resize((new_w, new_h), Image.LANCZOS)
    new_img = Image.new("RGB", target_size, (0, 0, 0))
    new_img.paste(img_resized, ((target_size[0]-new_w)//2, (target_size[1]-new_h)//2))
    return new_img


def generate_robust_mask(image: Image.Image):
    """
    Generate sharper mask, reduce gradient blur
    """
    orig_w, orig_h = image.size
    inputs = seg_processor(images=image, return_tensors="pt").to(device)
    with torch.no_grad():
        outputs = seg_model(**inputs)
        upsampled_logits = torch.nn.functional.interpolate(
            outputs.logits, size=(orig_h, orig_w), mode="bilinear", align_corners=False
        )
        pred = upsampled_logits.argmax(dim=1)[0].cpu().numpy()
    
    mask = np.zeros_like(pred, dtype=np.uint8)
    mask[(pred == 4) | (pred == 5) | (pred == 6) | (pred == 9)] = 255
    
    kernel_erode = cv2.getStructuringElement(cv2.MORPH_RECT, (3, 3))
    mask = cv2.erode(mask, kernel_erode, iterations=1)
    
    mask = cv2.morphologyEx(mask, cv2.MORPH_CLOSE, kernel_erode)
    
    mask = cv2.GaussianBlur(mask, (3, 3), 0)
    
    return Image.fromarray(mask)


def preprocess_warp_alignment(person_img: Image.Image, cloth_img: Image.Image):
    person_np = np.array(person_img)
    cloth_np = np.array(cloth_img)
    
    seg_mask = generate_robust_mask(person_img)
    mask_np = np.array(seg_mask)
    
    y_indices, x_indices = np.where(mask_np > 128)
    if len(x_indices) > 0 and len(y_indices) > 0:
        p_min_x, p_max_x = np.min(x_indices), np.max(x_indices)
        p_min_y, p_max_y = np.min(y_indices), np.max(y_indices)
        p_width = p_max_x - p_min_x
        p_height = p_max_y - p_min_y
        
        cloth_gray = cv2.cvtColor(cloth_np, cv2.COLOR_RGB2GRAY)
        _, cloth_thresh = cv2.threshold(cloth_gray, 10, 255, cv2.THRESH_BINARY)
        cx, cy, cw, ch = cv2.boundingRect(cloth_thresh)
        cropped_cloth = cloth_np[cy:cy+ch, cx:cx+cw]

        aligned_cloth_segment = cv2.resize(cropped_cloth, (p_width, p_height), interpolation=cv2.INTER_LANCZOS4)
        
        warped_cloth = np.zeros_like(cloth_np)
        warped_cloth[p_min_y:p_max_y, p_min_x:p_max_x] = aligned_cloth_segment
        
        mask_expanded = np.expand_dims(mask_np, axis=2) / 255.0
        blended_np = (warped_cloth * mask_expanded + person_np * (1.0 - mask_expanded)).astype(np.uint8)
        return Image.fromarray(blended_np), mask_np
        
    return person_img, mask_np


def update_redis_status(r_client, task_id, progress_percent, status, path=None):
    try:
        status_payload = {"status": status, "progress": progress_percent, "result_url": path}
        r_client.set(f"task_status:{task_id}", json.dumps(status_payload), ex=900)
    except Exception as e:
        print(f"[X] Redis Error: {e}")


def process_task(r_client, task_data):
    task_id = task_data.get("task_id")
    user_id = task_data.get("user_id")
    product_id = task_data.get("product_id")
    cloth_path_raw = task_data.get("product_img")
    color = task_data.get("color")
    base64_str = task_data.get("image_base64")

    print(f"\n[+] Processing High-Fidelity Task ID: {task_id}")
    try:
        if not base64_str or not cloth_path_raw:
            update_redis_status(r_client, task_id, 0, "failed")
            return

        cloth_path = os.path.join("picture-uploads", cloth_path_raw) if not cloth_path_raw.startswith("picture-uploads") else cloth_path_raw
        if not os.path.exists(cloth_path):
            update_redis_status(r_client, task_id, 0, "failed")
            return

        if "," in base64_str: base64_str = base64_str.split(",")[1]
        
        person_img_orig = Image.open(io.BytesIO(base64.b64decode(base64_str))).convert("RGB")
        cloth_img_orig = Image.open(cloth_path).convert("RGB")

        inference_size = (512, 768)
        person_img_low = resize_with_padding(person_img_orig, target_size=inference_size)
        cloth_img_low = resize_with_padding(cloth_img_orig, target_size=inference_size)

        person_blended, mask_np = preprocess_warp_alignment(person_img_low, cloth_img_low)

        edges = cv2.Canny(np.array(person_img_low), 50, 150)
        canny_image = Image.fromarray(np.stack([edges]*3, axis=-1))

        seed = torch.randint(0, 1_000_000, (1,)).item()
        generator = torch.Generator(device).manual_seed(seed)

        update_redis_status(r_client, task_id, 40, "processing")
        
        result = pipe(
            prompt=f"A highly professional studio fashion catalog photo of a person wearing a symmetrical clean {color} jacket garment item, perfectly fitted to body pose shape, realistic sleeves and elbows folds, accurate zipper closure line, model lookbook, 8k resolution, crisp texture",
            negative_prompt="crooked zipper, misplaced sleeves, asymmetrical arms, deformed elbows, cut-off torso, blurry anatomy, low fidelity, bad layout, shifted left, shifted right, bad body proportion",
            image=person_blended,
            mask_image=Image.fromarray(mask_np),
            control_image=canny_image,
            ip_adapter_image=[cloth_img_low], 
            num_inference_steps=35,
            strength=0.75,
            generator=generator,
            guidance_scale=7.5
        )

        final_image_low = result.images[0] if hasattr(result, "images") and result.images is not None else result[0]
        
        # Upscale
        target_high_res = (1024, 1536)
        person_high_res = resize_with_padding(person_img_orig, target_size=target_high_res)
        ai_output_high_res = final_image_low.resize(target_high_res, Image.LANCZOS)
        
        mask_high_res = generate_robust_mask(person_high_res)
        mask_high_res_np = np.array(mask_high_res)
        if mask_high_res_np.ndim == 3: mask_high_res_np = mask_high_res_np[:, :, 0]

        person_hr_np = np.array(person_high_res)
        ai_hr_np = np.array(ai_output_high_res)
        
        alpha = mask_high_res_np.astype(float) / 255.0
        alpha = np.expand_dims(alpha, axis=2) 
        
        composite_np = (ai_hr_np * alpha + person_hr_np * (1.0 - alpha)).astype(np.uint8)
        final_image = Image.fromarray(composite_np)

        # Insert 
        output_dir = os.path.join("static", "outputs", f"user_{user_id}")
        os.makedirs(output_dir, exist_ok=True)
        filename = f"output_{seed}.png"
        output_path = os.path.join(output_dir, filename)
        relative_web_path = f"outputs/user_{user_id}/{filename}"
        final_image.save(output_path)
        print(f"Output at: {output_path}")

        try:
            db = mysql.connector.connect(**DB_CONFIG)
            cursor = db.cursor()
            cursor.execute(
                "INSERT INTO tryon (user_id, cloth_path, result_img, product_id) VALUES (%s, %s, %s, %s)",
                (user_id, cloth_path_raw, filename, product_id)
            )
            db.commit()
            cursor.close()
            db.close()
            update_redis_status(r_client, task_id, 100, "complete", path=relative_web_path)
            print(f"{filename} has been insert into the database")
        except Exception as db_err:
            update_redis_status(r_client, task_id, 100, "db_error", path=relative_web_path)

    except Exception as e:
        print(f"[X] Runtime Exception: {e}")
        update_redis_status(r_client, task_id, 0, "failed")


def main():
    print(f"\n[*] AI CORE ENGINE ONLINE - PERSPECTIVE FIXED V3.0.")
    try: r = get_redis_client()
    except: return

    while True:
        try:
            tasks = r.hgetall(PENDING_HASH)
            if not tasks:
                time.sleep(1)
                continue
            for task_id, raw_payload in tasks.items():
                if r.hdel(PENDING_HASH, task_id):
                    process_task(r, json.loads(raw_payload))
        except (redis.ConnectionError, redis.TimeoutError):
            time.sleep(5)
            try: r = get_redis_client()
            except: pass
        except KeyboardInterrupt:
            break

if __name__ == "__main__":
    main()