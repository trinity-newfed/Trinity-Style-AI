import os
import json
import asyncio
from fastapi import FastAPI, HTTPException, Query
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import StreamingResponse
from fastapi.concurrency import run_in_threadpool
import redis
import requests
import torch
from langchain_community.utilities import SQLDatabase
from langchain_community.agent_toolkits import create_sql_agent
from langchain_ollama import ChatOllama
from dotenv import load_dotenv

load_dotenv()

app = FastAPI(title="Trinity-Style API")

REDIS_HOST = os.getenv("REDIS_AI_HOST", "trinity_redis_ai")
REDIS_PORT = int(os.getenv("REDIS_AI_PORT", 6379))
REDIS_PASSWORD = os.getenv("REDIS_PASSWORD", None)

OLLAMA_BASE_URL = os.getenv("OLLAMA_BASE_URL", "http://trinity_ollama:11434")
OLLAMA_MODEL = os.getenv("OLLAMA_MODEL", "qwen2.5:7b")

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

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

redis_client = redis.Redis(
    host=REDIS_HOST, 
    port=REDIS_PORT, 
    password=REDIS_PASSWORD,
    db=0, 
    decode_responses=True
)

db_uri = "mysql+pymysql://root:root_password@trinity_db:3306/tf_database"
db = SQLDatabase.from_uri(
    db_uri, 
    include_tables=['products', 'vouchers', 'product_variant'],
    sample_rows_in_table_info=3
)

def check_ollama_model_availability(base_url: str, preferred_model: str) -> str:
    """
    Check if Ollama light models is available.
    Automatically fallback to lighter model.
    """
    try:
        response = requests.get(f"{base_url}/api/tags", timeout=3)
        if response.status_code == 200:
            models = [m["name"] for m in response.json().get("models", [])]
            print(f"[*] Available models: {models}")
            
            if preferred_model in models:
                return preferred_model
            
            fallbacks = ["qwen2.5:3b", "qwen2.5:1.5b", "qwen2.5:0.5b"]
            for fb in fallbacks:
                if fb in models:
                    print(f"[!] {preferred_model} is missing. Fallback: {fb}")
                    return fb
                    
            if models:
                print(f"[!] Try different model: {models[0]}")
                return models[0]
    except Exception as e:
        print(f"[!] Failed to connect: {e}")
    
    return preferred_model

active_model = check_ollama_model_availability(OLLAMA_BASE_URL, OLLAMA_MODEL)
print(f"[*] Model: {active_model}")

llm = ChatOllama(
    base_url=OLLAMA_BASE_URL, 
    model=active_model, 
    temperature=0
)

system_prompt = """You are an expert SQL assistant for the fashion store "Trinity-Style".
Your main job is to analyze the user query, generate the correct MySQL query, EXECUTE IT immediately using your database tools, and then present the actual data back to the user.

CRITICAL RULES FOR SQL GENERATION:
1. NEVER USE 'SELECT *'. You must always specify explicit column names (e.g., p.product_name, v.product_color).
2. DO NOT GUESS OR HALLUCINATE COLUMNS. Use ONLY the exact columns provided in the schema dictionary below.
3. ALWAYS apply 'LIMIT 10' to prevent overloading the database unless explicitly asked for a count.
4. If checking for text/names, ALWAYS use 'LIKE %keyword%' for loose matching.
5. If a query requires data from multiple tables, you MUST explicitly use 'JOIN ... ON ...'.
   - Relationship: products.id = product_variant.product_id

EXACT DATABASE SCHEMA DICTIONARY (USE THESE EXACT NAMES):
- Table `products`:
  * Key columns: `id`, `product_name`, `product_price`, `product_category`, `product_type`, `product_describe`, `product_is_delete` (0=active, 1=deleted), `product_state` ('active', 'inactive').
- Table `product_variant`:
  * Key columns: `id`, `product_id`, `product_price`, `product_color`, `product_size`, `product_stock`, `product_is_delete` (0=active, 1=deleted), `product_state` ('active', 'inactive').
- Table `vouchers`:
  * Key columns: `id`, `code`, `discount_amount`, `is_active`.

FEW-SHOT EXAMPLES CORRECTED FOR YOUR SCHEMA:

User: "Show me black winter coat"
SQL: SELECT p.id, p.product_name, v.product_color, v.product_stock FROM products p JOIN product_variant v ON p.id = v.product_id WHERE p.product_name LIKE '%winter coat%' AND v.product_color = 'black' AND p.product_is_delete = 0 AND v.product_is_delete = 0 LIMIT 10;

User: "Do you have any winter coat in stock?"
SQL: SELECT p.id, p.product_name, SUM(v.product_stock) as total_stock FROM products p JOIN product_variant v ON p.id = v.product_id WHERE p.product_name LIKE '%winter coat%' AND p.product_is_delete = 0 AND v.product_is_delete = 0 GROUP BY p.id HAVING SUM(v.product_stock) > 0 LIMIT 10;

SECURITY & EXECUTION BOUNDARIES:
- YOU MUST EXECUTE the query immediately using the database tools. DO NOT STOP after checking or formulating the query.
- NEVER ask the user for permission to run the query. (e.g., NEVER say "Would you like to proceed with this query?" or "Should I run this?"). Just execute it!
- If the tool execution returns no data, inform the user that the product is currently unavailable or out of stock.
- If the user asks you to DROP, DELETE, INSERT, UPDATE, or ALTER any data or table, you MUST refuse and reply: "I am only authorized to perform data retrieval operations."
- Never share raw SQL queries or database structures in your final response to the customer. Convert the SQL execution results into a friendly, professional sales assistant answer."""

agent_executor = create_sql_agent(
    llm, 
    db=db, 
    agent_type="tool-calling",
    system_message=system_prompt,
    verbose=True
)

def verify_and_consume_task(task_id: str) -> dict:
    redis_key = f"chat-ai-pending:{task_id}"
    
    try:
        exists = redis_client.exists(redis_key)
        print(f"DEBUG: Key {redis_key} exist? {exists}")
        
        task_data = redis_client.get(redis_key)
        print(f"DEBUG: {task_data}")
    except Exception as e:
        print(f"DEBUG: Redis Error: {e}")
        return None

    if not task_data:
        return None

    print("New chat accepted!")
    redis_client.delete(redis_key)
    
    try:
        return json.loads(task_data)
    except json.JSONDecodeError:
        return {"status": "valid"}

@app.get("/stream")
async def stream_ai(
    task_id: str = Query(..., description="Task ID from Redis"), 
    message: str = Query(..., description="User message")
):
    if not message.strip() or not task_id.strip():
        raise HTTPException(status_code=400, detail="Missing message or task_id.")

    try:
        task_info = await run_in_threadpool(verify_and_consume_task, task_id)
    except redis.RedisError as re:
        print(f"❌ Redis Error: {str(re)}")
        raise HTTPException(status_code=500, detail="Authentication service error.")

    if not task_info:
        raise HTTPException(status_code=403, detail="Invalid or expired task ID.")

    async def event_generator():
        try:
            response = await run_in_threadpool(agent_executor.invoke, {"input": message})
            final_text = response["output"]

            words = final_text.split(" ")
            for i, word in enumerate(words):
                chunk = word + (" " if i < len(words) - 1 else "")
                
                yield f"data: {json.dumps({'token': chunk})}\n\n"
                await asyncio.sleep(0.05)

            yield f"data: {json.dumps({'status': 'completed'})}\n\n"

        except Exception as e:
            print(f"❌ LLM Execution Error: {str(e)}")
            yield f"data: {json.dumps({'status': 'error', 'message': 'System error'})}\n\n"

    return StreamingResponse(event_generator(), media_type="text/event-stream")

if __name__ == '__main__':
    import uvicorn
    uvicorn.run(app, host='0.0.0.0', port=5000)