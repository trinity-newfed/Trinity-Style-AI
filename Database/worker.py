import os
import json
import redis
import time
from dotenv import load_dotenv

load_dotenv()

REDIS_HOST = os.getenv("REDIS_AI_HOST", "trinity_redis_ai")
REDIS_PORT = int(os.getenv("REDIS_AI_PORT", 6379))
REDIS_PASSWORD = os.getenv("REDIS_PASSWORD", None)

RAW_QUEUE = "ai_tryon_queue"
PENDING_HASH = "ai_pending_tasks"

def get_redis_client():
    return redis.Redis(
        host=REDIS_HOST,
        port=REDIS_PORT,
        password=REDIS_PASSWORD,
        db=0,
        decode_responses=True
    )

def start_ingestion():
    r = get_redis_client()
    print(f"[*] Ingestion Worker Is Ready")

    while True:
        try:
            task_node = r.brpop(RAW_QUEUE, timeout=0)
            if task_node:
                _, raw_data = task_node
                print("\n[+] New task accepted.")
                
                task_data = json.loads(raw_data)
                print(f"[*] Payload receive: {task_data}")
                task_id = task_data.get("task_id")

                if task_id:
                    status_payload = {
                        "status": "pending",
                        "progress": 0,
                        "message": "In waiting line for AI...",
                        "result_url": None
                    }
                    r.setex(f"task_status:{task_id}", 900, json.dumps(status_payload))
                    
                    r.hset(PENDING_HASH, task_id, raw_data)
                    print(f"[✓] Task ID: {task_id} Pending Success\n" + "="*50)

        except (redis.ConnectionError, redis.TimeoutError):
            print("[!] Lost Connect To Ingestion Worker. Restart After 5s...")
            time.sleep(5)
            r = get_redis_client()
        except KeyboardInterrupt:
            print("\n[!] Stopping Ingestion Worker")
            break
        except Exception as e:
            print(f"[X] Ingestion runtime: {e}")

if __name__ == "__main__":
    start_ingestion()