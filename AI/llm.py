import os
import json
import re
import asyncio
import httpx
from contextlib import asynccontextmanager
from fastapi import FastAPI, HTTPException, Query, Request
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import StreamingResponse
from fastapi.concurrency import run_in_threadpool
import redis
from sqlalchemy import create_engine, text
from sqlalchemy.pool import QueuePool
from dotenv import load_dotenv

load_dotenv()

REDIS_HOST = os.getenv("REDIS_AI_HOST", "trinity_redis_ai")
REDIS_PORT = int(os.getenv("REDIS_AI_PORT", 6379))
REDIS_PASSWORD = os.getenv("REDIS_PASSWORD", None)

OLLAMA_BASE_URL = os.getenv("OLLAMA_BASE_URL", "http://trinity_ollama:11434")
OLLAMA_MODEL = os.getenv("OLLAMA_MODEL", "qwen2.5:14b-instruct-q3_K_M") 
OLLAMA_ROUTER_MODEL = os.getenv("OLLAMA_ROUTER_MODEL", "qwen2.5:3b")

DB_HOST = os.getenv("DB_HOST", "trinity_db")
DB_USER = os.getenv("DB_USER", "chat-agent-support")
DB_PASSWORD = os.getenv("DB_PASSWORD", "chatagent123")
DB_NAME = os.getenv("DB_NAME", "TF_Database")
DB_PORT = int(os.getenv("DB_PORT", 3306))

redis_client = redis.Redis(
    host=REDIS_HOST, port=REDIS_PORT, password=REDIS_PASSWORD, db=0, decode_responses=True
)

TABLE_SCHEMAS = {
    "products": """CREATE TABLE products (
    id INT PRIMARY KEY,
    product_name VARCHAR(255),
    product_describe TEXT
);""",
    "product_variant": """CREATE TABLE product_variant (
    product_id INT,
    product_price DECIMAL(10, 2),
    product_color VARCHAR(50),
    product_size VARCHAR(50),
    product_stock INT,
    product_is_delete TINYINT,
    product_state VARCHAR(50)
);""",
    "vouchers": """CREATE TABLE vouchers (
    id INT PRIMARY KEY,
    voucher_discount INT,
    voucher_condition INT,
    voucher_max INT,
    voucher_type VARCHAR(50),
    voucher_min_tier VARCHAR(50),
    vouchcer_state VARCHAR(50),
    starts_date VARCHAR(255),
    end_date VARCHAR(255)
);""",
    "orders": """CREATE TABLE orders (
    id INT PRIMARY KEY,
    user_id VARCHAR(100) DEFAULT NULL,
    order_name VARCHAR(100) DEFAULT NULL,
    order_original_price DECIMAL(10, 2) DEFAULT NULL,
    order_delivery_fee DECIMAL(10, 2) DEFAULT NULL,
    discount INT DEFAULT NULL,
    ship_discount INT DEFAULT NULL,
    order_final_price DECIMAL(10, 2) DEFAULT NULL,
    order_address VARCHAR(255) DEFAULT NULL,
    order_state ENUM('success','cancel','delivery','delivered') DEFAULT NULL
);""",
    "order_items": """CREATE TABLE order_items (
    id INT PRIMARY KEY,
    order_id VARCHAR(100) DEFAULT NULL,
    product_name VARCHAR(100) DEFAULT NULL,
    product_id INT DEFAULT NULL,
    price DECIMAL(10, 2) DEFAULT NULL,
    img VARCHAR(100) DEFAULT NULL,
    color VARCHAR(100) DEFAULT NULL,
    size VARCHAR(100) DEFAULT NULL,
    quantity INT DEFAULT NULL
);"""
}

db_uri = f"mysql+pymysql://{DB_USER}:{DB_PASSWORD}@{DB_HOST}:{DB_PORT}/{DB_NAME}"

engine = create_engine(
    db_uri, 
    poolclass=QueuePool,
    pool_size=5,
    max_overflow=10,
    pool_recycle=3600
)

def execute_sql(query: str):
    try:
        with engine.connect() as conn:
            result = conn.execute(text(query))
            if result.returns_rows:
                rows = result.fetchall()
                return str([dict(row._mapping) for row in rows])
            return "Query executed successfully."
    except Exception as e:
        raise e

def call_ollama(model: str, prompt: str, format_json: bool = False, temperature: float = 0, num_ctx: int = 4096):
    url = f"{OLLAMA_BASE_URL.rstrip('/')}/api/chat"
    payload = {
        "model": model,
        "messages": [{"role": "user", "content": prompt}],
        "options": {
            "temperature": temperature,
            "num_ctx": num_ctx
        },
        "stream": False
    }
    if format_json:
        payload["format"] = "json"
    
    with httpx.Client(timeout=60.0) as client:
        response = client.post(url, json=payload)
        response.raise_for_status()
        data = response.json()
        return data.get("message", {}).get("content", "")

async def stream_ollama(model: str, prompt: str, temperature: float = 0, num_ctx: int = 4096):
    url = f"{OLLAMA_BASE_URL.rstrip('/')}/api/chat"
    payload = {
        "model": model,
        "messages": [{"role": "user", "content": prompt}],
        "options": {
            "temperature": temperature,
            "num_ctx": num_ctx
        },
        "stream": True
    }
    async with httpx.AsyncClient(timeout=120.0) as client:
        async with client.stream("POST", url, json=payload) as response:
            response.raise_for_status()
            async for line in response.aiter_lines():
                if line.strip():
                    try:
                        chunk_data = json.loads(line)
                        content = chunk_data.get("message", {}).get("content", "")
                        if content:
                            yield content
                    except json.JSONDecodeError:
                        pass

def expand_dependent_tables(tables: list) -> list:
    expanded = set(tables)
    if "products" in expanded or "product_variant" in expanded:
        expanded.add("products")
        expanded.add("product_variant")
    if "orders" in expanded or "order_items" in expanded:
        expanded.add("orders")
        expanded.add("order_items")
    return list(expanded)

GREETING_PATTERNS = re.compile(
    r"^(hi|hello|hey|greetings|good\s+(morning|afternoon|evening)|sup|howdy|what's up)[\s!.]*$",
    re.IGNORECASE
)

def is_greeting(text: str) -> bool:
    cleaned = text.strip()
    if len(cleaned) < 30 and GREETING_PATTERNS.match(cleaned):
        return True
    return False

@asynccontextmanager
async def lifespan(app: FastAPI):
    print(f"[*] Initializing Pipeline: Main ({OLLAMA_MODEL}) & Router ({OLLAMA_ROUTER_MODEL})...")
    try:
        await run_in_threadpool(call_ollama, OLLAMA_ROUTER_MODEL, '{"tables": ["products"], "requires_user_context": false, "reason": "warmup"}', format_json=True)
        
        warmup_schema = "\n\n".join([TABLE_SCHEMAS["products"], TABLE_SCHEMAS["product_variant"]])
        warmup_prompt = f"""You are an expert SQL generator for "Trinity-Style" store. 
Given the user question and the specific database schema below, output ONLY a valid, syntactically correct MySQL query inside a markdown code block (```sql ... ```). Do not include any explanations.

RELEVANT DATABASE SCHEMA:
{warmup_schema}

RULES:
1. Write a select ONLY query to answer the user question using ONLY the provided tables above.
2. If `products` and `product_variant` are present, JOIN them on `products.id = product_variant.product_id`.
3. Filter active items: `product_variant.product_is_delete = 0 AND product_variant.product_state = 'active'` (if applicable).
4. ALWAYS apply `LIMIT 10`.

User Question: Find black summer shirt"""
        
        await run_in_threadpool(call_ollama, OLLAMA_MODEL, warmup_prompt)
        print("[+] Warm-up completed successfully. KV Cache is fully primed in VRAM!")
    except Exception as e:
        print(f"[!] Warm-up warning: {e}")
    yield

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
    except Exception:
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
    request: Request, 
    task_id: str = Query(..., description="Task ID from Redis"), 
    message: str = Query(..., description="User message")
):
    if not message.strip() or not task_id.strip():
        raise HTTPException(status_code=400, detail="Missing message or task_id.")

    try:
        task_info = await run_in_threadpool(verify_and_consume_task, task_id)
    except redis.RedisError:
        raise HTTPException(status_code=500, detail="Authentication service error.")

    if not task_info:
        raise HTTPException(status_code=403, detail="Invalid task ID.")

    async def event_generator():
        if is_greeting(message):
            greeting_response = "Hello! Welcome to Trinity-Style. How can I help you find your favorite outfits or vouchers today?"
            yield f"data: {json.dumps({'token': greeting_response})}\n\n"
            yield f"data: {json.dumps({'status': 'completed'})}\n\n"
            return

        router_prompt = f"""Analyze the user query and select ONLY the relevant core tables from [products, product_variant, vouchers, orders, order_items].
- Use [products, product_variant] for searching clothes, items, prices, colors, sizes, stock.
- Use [vouchers] for discount codes, promos.
- Use [orders, order_items] ONLY if the user asks about their past purchases, order history, or tracking their orders.
- Set "requires_user_context" to true ONLY IF [orders] or [order_items] are included in tables. Otherwise false.

User Query: "{message}"
Return strict JSON format:
{{"tables": ["table_name_1"], "requires_user_context": false, "reason": "short explanation"}}"""

        try:
            router_res = await run_in_threadpool(call_ollama, OLLAMA_ROUTER_MODEL, router_prompt, format_json=True)
            print(f"[-] Router Raw Response: {router_res}")
            routing_data = json.loads(router_res)
            raw_tables = routing_data.get("tables", [])
            requires_user_context = routing_data.get("requires_user_context", False)
            
            if not raw_tables:
                msg_lower = message.lower()
                if any(kw in msg_lower for kw in ["order", "orders", "history", "bought", "purchase", "đơn hàng", "lịch sử"]):
                    raw_tables = ["orders", "order_items"]
                    requires_user_context = True
                    print("[!] Router left 'tables' empty. Fallback triggered for orders.")

            if not raw_tables or not isinstance(raw_tables, list):
                refusal_msg = "I'm sorry, but I cannot assist with this request due to security and store policy guidelines."
                yield f"data: {json.dumps({'token': refusal_msg})}\n\n"
                yield f"data: {json.dumps({'status': 'completed'})}\n\n"
                return

            target_tables = expand_dependent_tables(raw_tables)
        except Exception as e:
            print(f"[!] Router Error: {e}")
            refusal_msg = "I'm sorry, but I cannot process this request due to security guidelines."
            yield f"data: {json.dumps({'token': refusal_msg})}\n\n"
            yield f"data: {json.dumps({'status': 'completed'})}\n\n"
            return

        user_context_str = ""
        if requires_user_context:
            user_id = task_info.get("user_id") or task_info.get("userId")
            if user_id:
                user_context_str = f"\nAUTHENTICATED USER CONTEXT:\n- Current Logged-in User ID: '{user_id}'\n(You MUST filter orders/items belonging to this user_id where applicable)."
            else:
                user_context_str = "\nAUTHENTICATED USER CONTEXT:\n- No valid user_id found in session. User might be unauthenticated."

        dynamic_schemas_str = "\n\n".join([TABLE_SCHEMAS[t] for t in target_tables if t in TABLE_SCHEMAS])

        sql_generation_prompt = f"""You are an expert SQL generator for "Trinity-Style" store. 
Given the user question and the specific database schema below, output ONLY a valid, syntactically correct MySQL query inside a markdown code block (```sql ... ```). Do not include any explanations.

RELEVANT DATABASE SCHEMA:
{dynamic_schemas_str}
{user_id}

RULES:
1. Write a select ONLY query to answer the user question using ONLY the provided tables above.
2. If `products` and `product_variant` are present, JOIN them on `products.id = product_variant.product_id`.
3. If `orders` or `order_items` are present, you MUST strictly apply the filter for the authenticated user ID provided in the SECURITY context. If you use a table alias (like `orders o`), use `o.user_id`.
4. For product types (like 'shirt', 'pant'), search in `products.product_name LIKE '%shirt%'`.
5. For size (`product_size`), only use actual sizes like 'S', 'M', 'L', 'XL'. Never put product names into `product_size`.
6. Filter active items: `product_variant.product_is_delete = 0 AND product_variant.product_state = 'active'`.
7. ALWAYS apply `LIMIT 10`.

User Question: {message}"""

        try:
            sql_text = await run_in_threadpool(call_ollama, OLLAMA_MODEL, sql_generation_prompt)
            
            sql_match = re.search(r"```sql\s*(.*?)\s*```", sql_text, re.DOTALL)
            if sql_match:
                sql_query = sql_match.group(1).strip()
            else:
                sql_query = sql_text.strip()

            print(f"[+] Target Tables chosen by Router: {target_tables}")
            print(f"[+] LLM Generated SQL (Raw): {sql_query}")
        except Exception as e:
            print(f"[!] SQL Generation Error: {e}")
            yield f"data: {json.dumps({'token': 'I encountered an issue processing your request.'})}\n\n"
            yield f"data: {json.dumps({'status': 'completed'})}\n\n"
            return

        cleaned_sql = sql_query.upper()
        if not cleaned_sql.startswith("SELECT") or "DEF " in cleaned_sql or "IMPORT " in cleaned_sql:
            refusal_msg = "I'm sorry, but I cannot fulfill that request. I am only able to help you find products and vouchers at Trinity-Style."
            yield f"data: {json.dumps({'token': refusal_msg})}\n\n"
            yield f"data: {json.dumps({'status': 'completed'})}\n\n"
            return

        if requires_user_context or "ORDERS" in cleaned_sql or "ORDER_ITEMS" in cleaned_sql:
            session_user_id = task_info.get("user_id") or task_info.get("userId")
            
            if not session_user_id:
                refusal_msg = "I'm sorry, but you need to be logged in to view your orders."
                yield f"data: {json.dumps({'token': refusal_msg})}\n\n"
                yield f"data: {json.dumps({'status': 'completed'})}\n\n"
                return

            print(f"[+] Enforcing Security for Session User ID: {session_user_id}")

            pattern_user_id_check = r"(?:[a-zA-Z0-9_]+\.)?user_id\s*=\s*(?:'[^']*'|\"[^\"]*\"|\d+)"
            
            if re.search(pattern_user_id_check, sql_query, re.IGNORECASE):
                sql_query = re.sub(
                    r"((?:[a-zA-Z0-9_]+\.)?user_id\s*=\s*)(?:'[^']*'|\"[^\"]*\"|\d+)",
                    rf"\1'{session_user_id}'",
                    sql_query,
                    flags=re.IGNORECASE
                )
            else:
                if "WHERE" in cleaned_sql:
                    sql_query = re.sub(r"WHERE", f"WHERE orders.user_id = '{session_user_id}' AND", sql_query, flags=re.IGNORECASE, count=1)
                else:
                    if "FROM ORDERS" in cleaned_sql:
                        sql_query = re.sub(r"(FROM\s+orders(?:\s+[a-zA-Z0-9_]+)?)", rf"\1 WHERE user_id = '{session_user_id}'", sql_query, flags=re.IGNORECASE, count=1)
                    else:
                        sql_query = re.sub(r"(JOIN\s+orders\s+[a-zA-Z0-9_]+)", rf"\1 ON orders.user_id = '{session_user_id}' AND", sql_query, flags=re.IGNORECASE, count=1)

            print(f"[+] Secured Final SQL: {sql_query}")



        try:
            db_result = await run_in_threadpool(execute_sql, sql_query)
            print(f"[+] DB Result: {db_result}")
        except Exception as e:
            print(f"[!] DB Execution Error: {e}")
            db_result = "No matching records found."

        summary_prompt = f"""You are a helpful customer service assistant for "Trinity-Style" store.
User Question: "{message}"
Database Query Results: {db_result}

INSTRUCTIONS:
- You MUST base your answer EXCLUSIVELY on the actual Database Query Results provided above.
- NEVER invent, fabricate, or hallucinate names, addresses, user IDs, or numbers that are not explicitly present in the Database Query Results.
- If the results contain sensitive personal data (like order addresses or user IDs) and the user cannot be verified as the owner, politely refuse to disclose them.
- Answer the customer naturally and politely IN ENGLISH. DO NOT mention database, tables, SQL queries, or technical steps."""

        try:
            async for chunk in stream_ollama(OLLAMA_MODEL, summary_prompt):
                if await request.is_disconnected():
                    print("[!] Client disconnected. Aborting response streaming.")
                    break
                
                if chunk:
                    yield f"data: {json.dumps({'token': chunk})}\n\n"

            yield f"data: {json.dumps({'status': 'completed'})}\n\n"

        except asyncio.CancelledError:
            print("[!] Stream task cancelled by client.")
            raise
        except Exception as e:
            print(f"[!] Summary Streaming Error: {e}")
            yield f"data: {json.dumps({'token': 'I encountered an issue formatting the results.'})}\n\n"
            yield f"data: {json.dumps({'status': 'completed'})}\n\n"

    return StreamingResponse(event_generator(), media_type="text/event-stream")

if __name__ == '__main__':
    import uvicorn
    uvicorn.run(app, host='0.0.0.0', port=5000)