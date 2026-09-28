import os
import json
import asyncio
from contextlib import asynccontextmanager
from fastapi import FastAPI, HTTPException, Query
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import StreamingResponse
from fastapi.concurrency import run_in_threadpool
import redis
from sqlalchemy.pool import QueuePool
from langchain_community.utilities import SQLDatabase
from langchain_community.agent_toolkits.sql.toolkit import SQLDatabaseToolkit
from langchain_community.agent_toolkits import create_sql_agent
from langchain_ollama import ChatOllama
from dotenv import load_dotenv

load_dotenv()

REDIS_HOST = os.getenv("REDIS_AI_HOST", "trinity_redis_ai")
REDIS_PORT = int(os.getenv("REDIS_AI_PORT", 6379))
REDIS_PASSWORD = os.getenv("REDIS_PASSWORD", None)

OLLAMA_BASE_URL = os.getenv("OLLAMA_BASE_URL", "http://trinity_ollama:11434")
OLLAMA_MODEL = os.getenv("OLLAMA_MODEL", "qwen2.5:14b")

DB_HOST = os.getenv("DB_HOST", "trinity_db")
DB_USER = os.getenv("DB_USER", "chat-agent-support")
DB_PASSWORD = os.getenv("DB_PASSWORD", "chatagent123")
DB_NAME = os.getenv("DB_NAME", "tf_database")
DB_PORT = int(os.getenv("DB_PORT", 3306))

redis_client = redis.Redis(
    host=REDIS_HOST, port=REDIS_PORT, password=REDIS_PASSWORD, db=0, decode_responses=True
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
    sample_rows_in_table_info=0,
    indexes_in_table_info=False, 
    engine_args={
        "poolclass": QueuePool,
        "pool_size": 5,
        "max_overflow": 10,
        "pool_recycle": 3600
    },
    custom_table_info=custom_schema
)

llm = ChatOllama(
    base_url=OLLAMA_BASE_URL, 
    model=OLLAMA_MODEL, 
    temperature=0,
    num_ctx=4096,
    streaming=True
)

toolkit = SQLDatabaseToolkit(db=db, llm=llm)
all_tools = toolkit.get_tools()
fast_tools = [t for t in all_tools if t.name == "sql_db_query"]

react_prefix = """You are an agent designed to interact with a SQL database for "Trinity-Style" store.
Given an input question, IMMEDIATELY create a syntactically correct MySQL query and execute it using `sql_db_query`.

CRITICAL INSTRUCTIONS TO BE FAST:
- DO NOT list tables or ask for schema. You ALREADY know the database schema below.
- DO NOT use any checker tools. Execute `sql_db_query` immediately in your first Action.

SCHEMA INFORMATION:
- `products`: id, product_name, product_describe
- `product_variant`: product_id, product_price, product_color, product_size, product_stock, product_is_delete, product_state
- `vouchers`: id, voucher_discount, voucher_condition, voucher_max, voucher_type, voucher_min_tier, vouchcer_state, starts_date, end_date

ROUTING RULES:
1. SEARCH BY ITEM TYPE/NAME (coat, shirt, tank crop top, etc.):
   - JOIN `products` with `product_variant` (`products.id = product_variant.product_id`).
   - WHERE `products.product_name LIKE '%keyword%'` AND `product_variant.product_is_delete = 0` AND `product_variant.product_state = 'active'`.
2. ALWAYS apply `LIMIT 10`.

STRICT OUTPUT RULES:
- Output ONLY the final response meant for the customer IN ENGLISH.
- DO NOT mention SQL, database, tables, columns, or execution steps.
"""

agent_executor = create_sql_agent(
    llm, 
    toolkit=toolkit,
    agent_type="zero-shot-react-description",
    extra_tools=fast_tools,
    prefix=react_prefix,
    max_iterations=4,
    early_stopping_method="force",
    verbose=False
)

@asynccontextmanager
async def lifespan(app: FastAPI):
    print(f"Model: {OLLAMA_MODEL}...")
    try:
        await run_in_threadpool(agent_executor.invoke, {"input": "Ping"})
    except Exception as e:
        print(f"[!] Pre-load warning: {e}")
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
        try:
            buffer = ""
            final_answer_found = False
            marker = "final answer:"

            async for event in agent_executor.astream_events({"input": message}, version="v2"):
                kind = event["event"]

                if kind == "on_chat_model_start":
                    buffer = ""
                    final_answer_found = False

                elif kind == "on_chat_model_stream":
                    content = event["data"]["chunk"].content
                    if not content:
                        continue

                    if final_answer_found:
                        yield f"data: {json.dumps({'token': content})}\n\n"
                    else:
                        buffer += content
                        lower_buf = buffer.lower()
                        if marker in lower_buf:
                            final_answer_found = True
                            idx = lower_buf.find(marker) + len(marker)
                            remaining_text = buffer[idx:].lstrip()
                            if remaining_text:
                                yield f"data: {json.dumps({'token': remaining_text})}\n\n"
                            buffer = ""

            if not final_answer_found and buffer.strip():
                if "Action:" not in buffer and "Thought:" not in buffer:
                    yield f"data: {json.dumps({'token': buffer.strip()})}\n\n"

            yield f"data: {json.dumps({'status': 'completed'})}\n\n"

        except Exception as e:
            print(f"[!] Streaming Error: {str(e)}")
            fallback_msg = "I encountered an issue checking our inventory. Please try again."
            yield f"data: {json.dumps({'token': fallback_msg})}\n\n"
            yield f"data: {json.dumps({'status': 'completed'})}\n\n"

    return StreamingResponse(event_generator(), media_type="text/event-stream")

if __name__ == '__main__':
    import uvicorn
    uvicorn.run(app, host='0.0.0.0', port=5000)