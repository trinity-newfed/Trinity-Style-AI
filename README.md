# TRINITY STYLE AI
A premium fashion e-commerce ecosystem featuring an integrated AI Virtual Try-on engine, bridging the gap between luxury retail and computer vision technology.

### Overview
Trinity Style is a full-stack graduation project designed to redefine the online shopping experience. By combining a Luxury Minimalist aesthetic with state-of-the-art AI, the platform allows users to visualize garments on their own photos, reducing return rates and enhancing engagement.
<details>
<video src="https://github.com/user-attachments/assets/b17e9e53-b0be-4a75-9305-849af284a77c"></video>
</details>
✨ Core Features
🎨 Luxury Minimalist UI: High-contrast, black-and-white design language focused on premium brand identity

🤖 AI Virtual Try-on: Leverages SegFormer for precise segmentation and ControlNet to generate realistic clothing overlays

🔒 Secure Infrastructure: Features a robust PHP backend with OTP authentication and secure SQL transaction logic

📊 User Tier System: Specialized logic for loyalty tiers and personalized "Try-on History" archives


### 💻 Tech Stack
Frontend: HTML5, CSS3, JavaScript
Backend: PHP, Python(Flask/FastAPI), MySQL

AI/ML: SegFormer, ControlNet, IP-Adapter
Environment: macOS / Windows Cross-platform development

### 🛠 Installation & Setup
##### Clone the repository:

git clone https://github.com/trinity-newfed/Trinity-Style-AI.git



https://github.com/user-attachments/assets/907da0ec-a473-4ed2-826a-cde5ee686c04



## Backend setup: 
! This project uses **Composer** to manage PHP libraries. Make sure you have Composer installed before proceeding.

### MAILER
#### PHP Composer Download:
https://getcomposer.org/Composer-Setup.exe

#### Environment Configuration: 
composer require vlucas/phpdotenv

#### Mailer Integration: 
composer require phpmailer/phpmailer

## REQUIRE FOR SEGFORMER
The AI engine requires **Python 3.10.11**. Note that the SegFormer model download exceeds **5.4GB** on the first run.
Python: https://www.python.org/downloads/release/python-31011/


#### 🛰 Pipeline Architecture
1.  **SAM (Segment Anything):** For initial segmentation.
2.  **Mask Generation:** Creating the inpainting area.
3.  **ControlNet & IP-Adapter:** To maintain garment texture and structure.
4.  **Stable Diffusion 1.5 Inpainting:** Final image synthesis.

### Virtual Environment Setup
cd AI
python -m venv sd15

### Activate environment
sd15\Scripts\activate

### Activate on Windows (PowerShell)
#### If blocked, run:
1. Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass
2. sd15\Scripts\Activate.ps1

### Standard dependencies
pip install torch torchvision diffusers transformers safetensors accelerate

### Specific CPU Torch Installation (Required for stability on non-GPU systems)
pip install torch==2.6.0 --index-url https://download.pytorch.org/whl/cpu

### Database & API Bridge
pip install mysql-connector-python flask-cors

### Requirements.txt
pip install -r requirements.txt

## Documentation

### 🎬PHP MAILER SET UP VIDEO
#### 1. Composer Set Up


https://github.com/user-attachments/assets/cc1d23ae-a700-4db6-ac55-361c17de06be


#### 2. PHP Mailer Set Up


https://github.com/user-attachments/assets/bdad452c-ba02-48d5-ba3a-682ede1fb063



### 🎬PYTHON AND VIRTUAL ENVIRONMENT SET UP VIDEO
#### Python Download Set Up


https://github.com/user-attachments/assets/74b8635d-dc7a-4f48-8666-985813320484



#### Virtual Environment Set Up
https://github.com/user-attachments/assets/38f5672e-3a06-48e3-bdf1-04fc3ca82ca9



