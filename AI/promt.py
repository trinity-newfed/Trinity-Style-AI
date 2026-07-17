from flask import Flask, request, jsonify
from flask_cors import CORS
from langchain_community.utilities import SQLDatabase
from langchain_community.agent_toolkits import create_sql_agent
from langchain_ollama import ChatOllama

app = Flask(__name__)
CORS(app)

# 1. Kết nối Database MySQL
db_uri = "mysql+pymysql://root:root_password@trinity_db:3306/tf_database"
db = SQLDatabase.from_uri(
    db_uri, 
    include_tables=['products', 'vouchers', 'product_variant'],
    sample_rows_in_table_info=3
)

# 2. Khởi tạo LLM
llm = ChatOllama(
    base_url="http://trinity_ollama:11434", 
    model="qwen2.5:7b", 
    temperature=0
)

# 3. Định nghĩa System Prompt thuần túy
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

# 5. API Endpoint
@app.route('/predict', methods=['POST'])
def predict():
    try:
        data = request.get_json() or {}
        user_message = data.get("message", "").strip()

        if not user_message:
            return jsonify({"response": "Please provide a message."}), 400

        response = agent_executor.invoke({"input": user_message})
        return jsonify({"response": response["output"]})

    except Exception as e:
        print(f"❌ Error: {str(e)}")
        return jsonify({"response": "Sorry, I encountered an error processing that request."}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=False)