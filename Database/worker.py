import json
import os
import redis
from dotenv import load_dotenv

load_dotenv()

REDIS_HOST = os.getenv("REDIS_AI_SERVICE_HOST")
REDIS_PORT = int(os.getenv("REDIS_PORT"))
REDIS_PASSWORD = os.getenv("REDIS_PASSWORD", None)

try:
    r = redis.Redis(
        host=REDIS_HOST,
        port=REDIS_PORT,
        password=REDIS_PASSWORD,
        decode_responses=True,
        protocol=2
    )
    print("[*] Ingestion Worker đã kết nối Redis.")
except Exception as e:
    print(f"[!] Lỗi kết nối Redis: {e}")
    exit(1)

RAW_QUEUE = "ai_tryon_queue"
PENDING_HASH = "ai_pending_tasks"

def start_ingestion():
    print(f"[*] Đang lắng nghe từ '{RAW_QUEUE}' để lưu Task...\n" + "="*50)

    while True:
        try:
            _, raw_data = r.brpop(RAW_QUEUE, timeout=0)

            if raw_data:
                # -------------------------------------------------------------
                # PRINT NGUYÊN CỤC JSON NHẬN TỪ PHP PROXY
                # -------------------------------------------------------------
                print("\n[+] NHẬN ĐƯỢC TASK MỚI TỪ PHP PROXY:")
                print("RAW JSON STRING:")
                print(raw_data)  # In nguyên chuỗi JSON thô nhận từ Queue

                task_data = json.loads(raw_data)
                
                # In JSON đã được định dạng đẹp (Pretty Print) cho dễ đọc
                print("\nPARSED JSON DATA:")
                print(json.dumps(task_data, indent=4, ensure_ascii=False))
                print("-" * 50)

                task_id = task_data.get("task_id")

                if task_id:
                    status_payload = {
                        "status": "pending",
                        "progress": 0,
                        "message": "Đang trong hàng chờ xử lý AI...",
                        "result_url": None
                    }
                    r.setex(f"task_status:{task_id}", 600, json.dumps(status_payload))

                    r.hset(PENDING_HASH, task_id, json.dumps(task_data))

                    print(f"[✓] Đã tiếp nhận & lưu Task ID: {task_id}\n" + "="*50)

        except KeyboardInterrupt:
            print("\n[*] Dừng Ingestion Worker.")
            break
        except Exception as e:
            print(f"[!] Lỗi Ingestion: {e}")

if __name__ == "__main__":
    start_ingestion()