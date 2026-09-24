import os
import json
import asyncio
from contextlib import asynccontextmanager
from fastapi import FastAPI, HTTPException, Query
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import StreamingResponse
from fastapi.concurrency import run_in_threadpool
import redis
import requests
import pymysql
from langchain_community.utilities import SQLDatabase
from langchain_community.agent_toolkits import create_sql_agent
from langchain_ollama import ChatOllama
from dotenv import load_dotenv

load_dotenv()

REDIS_HOST = os.getenv("REDIS_AI_HOST", "trinity_redis_ai")
REDIS_PORT = int(os.getenv("REDIS_AI_PORT", 6379))
REDIS_PASSWORD = os.getenv("REDIS_PASSWORD", None)

OLLAMA_BASE_URL = os.getenv("OLLAMA_BASE_URL", "http://trinity_ollama:11434")
OLLAMA_MODEL = os.getenv("OLLAMA_MODEL", "qwen2.5:7b")

DB_HOST = os.getenv("DB_HOST", "trinity_db")
DB_USER = os.getenv("DB_USER", "chat-agent-support")
DB_PASSWORD = os.getenv("DB_PASSWORD", "chatagent123")
DB_NAME = os.getenv("DB_NAME", "tf_database")
DB_PORT = int(os.getenv("DB_PORT", 3306))

redis_client = redis.Redis(
    host=REDIS_HOST, 
    port=REDIS_PORT, 
    password=REDIS_PASSWORD,
    db=0, 
    decode_responses=True
)

custom_schema = {
    "products": """
    CREATE TABLE products (
        id INT PRIMARY KEY,
        product_name VARCHAR(255),
        product_describe TEXT
    );
    """,
    "product_variant": """
    CREATE TABLE product_variant (
        product_id INT,
        product_price DECIMAL(10, 2),
        product_color VARCHAR(50),
        product_size VARCHAR(50),
        product_stock INT,
        product_is_delete TINYINT,
        product_state VARCHAR(50)
    );
    """,
    "vouchers": """
    CREATE TABLE vouchers (
        id INT PRIMARY KEY,
        voucher_discount INT,
        voucher_condition INT,
        voucher_max INT,
        voucher_type VARCHAR(50),
        voucher_min_tier VARCHAR(50),
        vouchcer_state VARCHAR(50),
        starts_date VARCHAR(255),
        end_date VARCHAR(255)
    );
    """
}

db_uri = f"mysql+pymysql://{DB_USER}:{DB_PASSWORD}@{DB_HOST}:{DB_PORT}/{DB_NAME}"
db = SQLDatabase.from_uri(
    db_uri, 
    include_tables=['products', 'vouchers', 'product_variant'],
    sample_rows_in_table_info=3,
    custom_table_info=custom_schema
)

def check_ollama_model_availability(base_url: str, preferred_model: str) -> str:
    try:
        response = requests.get(f"{base_url}/api/tags", timeout=3)
        if response.status_code == 200:
            models = [m["name"] for m in response.json().get("models", [])]
            if preferred_model in models:
                return preferred_model
            fallbacks = ["qwen2.5:3b", "qwen2.5:1.5b", "qwen2.5:0.5b"]
            for fb in fallbacks:
                if fb in models:
                    return fb
            if models:
                return models[0]
    except Exception:
        pass
    return preferred_model

active_model = check_ollama_model_availability(OLLAMA_BASE_URL, OLLAMA_MODEL)
print(f"[*] Pre-loading Active Model: {active_model}")

llm = ChatOllama(
    base_url=OLLAMA_BASE_URL, 
    model=active_model, 
    temperature=0
)

system_prompt = """You are a helpful fashion store assistant for "Trinity-Style".
Your task is to answer user queries based on data retrieved from the database.

TABLE SEARCH ROUTING RULES (VERY IMPORTANT):
1. SEARCH BY PRODUCT NAME / ITEM TYPE (e.g., "shirt", "pant", "coat", "dress"):
   - You MUST search using `products.product_name` or `products.product_describe` FIRST.
   - ALWAYS JOIN `products` with `product_variant` (products.id = product_variant.product_id) to get stock, color, and price in ONE SINGLE QUERY.
2. SEARCH BY SPECIFIC ATTRIBUTES ONLY (e.g., color "red", size "XL", price range):
   - Query `product_variant` and JOIN `products` to get the product name.
3. SEARCH VOUCHERS / DISCOUNTS:
   - Query `vouchers` table.

CRITICAL RULES FOR SQL GENERATION:
1. NEVER USE 'SELECT *'. Always specify explicit column names (e.g., p.product_name, v.product_color, v.product_price).
2. DO NOT GUESS OR HALLUCINATE COLUMNS. Use ONLY these exact columns:
   - Table `products`: `id`, `product_name`, `product_describe`
   - Table `product_variant`: `product_id`, `product_price`, `product_color`, `product_size`, `product_stock`, `product_is_delete` (0=active, 1=deleted), `product_state` ('active', 'inactive')
   - Table `vouchers`: `id`, `voucher_discount`, `voucher_condition`, `voucher_max`, `voucher_type`, `voucher_min_tier`, `vouchcer_state`, `starts_date`, `end_date`
3. ALWAYS apply 'LIMIT 10' to queries unless explicitly asked for a count.
4. Always filter active items: `v.product_is_delete = 0` AND `v.product_state = 'active'`.
5. If checking for text/names, ALWAYS use 'LIKE %keyword%'.

STRICT OUTPUT RULES:
- Output ONLY the final response meant for the customer IN ENGLISH.
- DO NOT mention SQL, database, tables, columns, execution steps, or queries in your response.
- Directly give a friendly, professional response.
- If no data is found, politely respond that the item or voucher is not available.
- Refuse any request to Modify, Delete, Insert, or Alter data."""

agent_executor = create_sql_agent(
    llm, 
    db=db, 
    agent_type="tool-calling",
    system_message=system_prompt,
    early_stopping_method="force",
    verbose=True
)

@asynccontextmanager
async def lifespan(app: FastAPI):
    try:
        await run_in_threadpool(
            agent_executor.invoke, 
            {"input": "Ping database connection check"}
        )
    except Exception as e:
        print(f"Pre-load warning (App will continue running): {e}")
    
    yield
    print("App shutting down...")

app = FastAPI(title="Trinity-Style API", lifespan=lifespan)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

def verify_and_consume_task(task_id: str) -> dict:
    redis_key = f"chat-ai-pending:{task_id}"
    try:
        task_data = redis_client.get(redis_key)
    except Exception as e:
        print(f"DEBUG: Redis Error: {e}")
        return None

    if not task_data:
        return None

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
        print(f"[!] Redis Error: {str(re)}")
        raise HTTPException(status_code=500, detail="Authentication service error.")

    if not task_info:
        raise HTTPException(status_code=403, detail="Invalid or expired task ID.")

    async def event_generator():
        try:
            response = await run_in_threadpool(agent_executor.invoke, {"input": message})
            final_text = response.get("output", "I'm sorry, I couldn't find any relevant details for your request.")

            words = final_text.split(" ")
            for i, word in enumerate(words):
                chunk = word + (" " if i < len(words) - 1 else "")
                yield f"data: {json.dumps({'token': chunk})}\n\n"
                await asyncio.sleep(0.03)

            yield f"data: {json.dumps({'status': 'completed'})}\n\n"

        except Exception as e:
            print(f"[!] LLM Execution Error: {str(e)}")
            fallback_msg = "I'm sorry, I encountered an issue checking our inventory. Please try again or ask about another item."
            yield f"data: {json.dumps({'token': fallback_msg})}\n\n"
            yield f"data: {json.dumps({'status': 'completed'})}\n\n"

    return StreamingResponse(event_generator(), media_type="text/event-stream")

if __name__ == '__main__':
    import uvicorn
    uvicorn.run(app, host='0.0.0.0', port=5000)