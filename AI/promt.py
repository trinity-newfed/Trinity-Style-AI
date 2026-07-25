import os
import json
import asyncio
from fastapi import FastAPI, HTTPException, Query
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import StreamingResponse
from fastapi.concurrency import run_in_threadpool
import redis
from langchain_community.utilities import SQLDatabase
from langchain_community.agent_toolkits import create_sql_agent
from langchain_ollama import ChatOllama
from dotenv import load_dotenv

load_dotenv()

app = FastAPI(title="Trinity-Style API")

REDIS_HOST = os.getenv("REDIS_AI_HOST", "trinity_redis_ai")
REDIS_PORT = int(os.getenv("REDIS_AI_PORT", 6379))
REDIS_PASSWORD = os.getenv("REDIS_PASSWORD", None)

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

llm = ChatOllama(
    base_url="http://trinity_ollama:11434", 
    model="qwen2.5:7b", 
    temperature=0
)

system_prompt = """You are an expert SQL assistant for the fashion store "Trinity-Style"...""" 

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
        print(f"DEBUG: Key {redis_key} ton tai? {exists}")
        
        task_data = redis_client.get(redis_key)
        print(f"DEBUG: Data lay duoc: {task_data}")
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
    task_id: str = Query(..., description="Task ID từ Redis"), 
    message: str = Query(..., description="Tin nhắn của user")
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