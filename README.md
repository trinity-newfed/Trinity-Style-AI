#  TRINITY STYLE AI
> A premium fashion e-commerce ecosystem featuring an integrated AI Virtual Try-On engine and intelligent AI Customer Support, bridging the gap between luxury retail and computer vision technology.

---

###  Overview
**Trinity Style** is a full-stack e-commerce platform designed to redefine the online luxury shopping experience. By combining a **Luxury Minimalist** aesthetic with state-of-the-art Generative AI and Large Language Models (LLMs), Trinity Style enables customers to seamlessly visualize garments on their own photos and receive instant, personalized shopping assistance—reducing return rates and maximizing customer engagement.

https://github.com/user-attachments/assets/d64bc8fe-f0a3-47bc-a141-90d394a36aa2

---

### Core Features

*  **Luxury Minimalist UI:** High-contrast, black-and-white design language tailored for a high-end brand identity.
*  **AI Virtual Try-On (VITON):** Powered by **Stable Diffusion 1.5 Pipeline** utilizing **SegFormer** for ultra-precise garment segmentation, **ControlNet**, and **IP-Adapter** for realistic lighting and texture overlays.
*  **AI Customer Support:** Integrated **Qwen2.5 (14B)** LLM delivering real-time, context-aware product queries and customer assistance.
*  **High-Performance Caching:** **Redis** integration for lightning-fast session management and AI inference queueing.
*  **Secure Infrastructure:** Robust backend architecture featuring OTP authentication and secure transactional database logic.
*  **User Tier System:** Specialized business logic for customer loyalty tiers and personalized "Try-On History" archives.

---

### 🛠 Tech Stack

* **Frontend:** HTML5, CSS3, JavaScript (Tailwind CSS)
* **Backend:** PHP, Python (FastAPI / Flask), MySQL, **Redis**
* **AI / ML / LLM:** 
  * **Virtual Try-On:** Stable Diffusion 1.5 Pipeline (SegFormer, ControlNet, IP-Adapter)
  * **Chatbot Support:** Qwen2.5 (14B)
* **Hardware Acceleration:** NVIDIA CUDA, AMD ROCm, CPU (Tested)
* **Supported OS:** Ubuntu Linux, macOS, Windows (Cross-Platform)

---

### 💻 Hardware Requirements

Because the platform runs a **14B LLM** alongside a **Stable Diffusion 1.5 pipeline**, the following hardware specifications are recommended:

| Resource | Minimum Requirement | Recommended Specification |
| :--- | :--- | :--- |
| **GPU VRAM** | 8 GB VRAM | **> 12 GB VRAM** (NVIDIA CUDA or AMD ROCm) |
| **System RAM**| 16 GB RAM | **> 32 GB RAM** |
| **Storage** | 30 GB free space (SSD) | 50 GB+ High-Speed NVMe SSD |
| **CPU** | 4-Core CPU | 8-Core CPU (x86_64) |

> ! **Hardware Acceleration Note:** 
> * **NVIDIA CUDA & AMD ROCm:** Fully supported and tested for low-latency AI generation.
> * **CPU Mode:** Fully tested and functional, but **NOT recommended** for production due to long inference times for both LLM and VITON pipelines.

---

### 🎬 Installation & Setup Guide

##### Ubuntu Linux (26.04) / Debian-based Systems

Quick linux command: chmod +x ./operate.sh

https://github.com/user-attachments/assets/c95b6dfc-53ad-4610-9914-c85b9cefafb9

