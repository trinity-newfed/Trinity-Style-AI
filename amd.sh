#!/bin/bash

export HSA_OVERRIDE_GFX_VERSION=11.0.0
export PYTORCH_ROCM_ARCH=gfx1100 
export TORCH_BLAS_PREFER_HIPBLASLT=0
export USE_HIPBLASLT=0
export ROCM_PATH=/opt/rocm
export HIP_VISIBLE_DEVICES=0
export HSA_ENABLE_SDMA=0
export AMD_SERIALIZE_KERNEL=1
export AMD_SERIALIZE_COPY=1
export PYTORCH_CUDA_ALLOC_CONF="garbage_collection_threshold:0.6,max_split_size_mb:64"

echo "=========================================="
echo "[AMD Setup]..."
echo "=========================================="
sudo chmod a+rw /dev/kfd 2>/dev/null || true
sudo chmod a+rw /dev/dri/renderD128 2>/dev/null || true
sudo usermod -a -G render,video $USER

echo "[+] PCIe ASPM for GPU..."
sudo bash -c 'echo performance > /sys/module/pcie_aspm/parameters/policy'

sudo bash -c 'cat <<EOF > /etc/systemd/system/egpu-performance.service
[Unit]
Description=Set PCIe ASPM to Performance for GPU stability
After=multi-user.target

[Service]
Type=oneshot
ExecStart=/bin/sh -c "echo performance > /sys/module/pcie_aspm/parameters/policy"

[Install]
WantedBy=multi-user.target
EOF'

sudo systemctl daemon-reload
sudo systemctl enable --now egpu-performance.service

echo "[✔] Done! Performance mode permanent."

VENV_DIR="venv-sd15"
AI_DIR="AI"

echo "=========================================="
echo "[AMD Setup] Checking Native Host Environment..."
echo "=========================================="

echo "[+] Installing system dependencies, python3.11 & patchelf..."
sudo apt update && sudo apt install -y \
    python3.11 \
    python3.11-dev \
    python3.11-venv \
    python3-pip \
    pkg-config \
    build-essential \
    curl \
    patchelf \
    execstack || true

PYTHON_BIN="python3.11"
echo "[+] Using Python interpreter: $PYTHON_BIN"

echo "[+] Cleaning up old virtual environment..."
rm -rf "$VENV_DIR"

echo "[+] Creating fresh virtual environment: $VENV_DIR..."
$PYTHON_BIN -m venv "$VENV_DIR"

if [ ! -f "$VENV_DIR/bin/activate" ]; then
    echo "[!] Error: Virtual environment creation failed!"
    exit 1
fi

source "$VENV_DIR/bin/activate"

echo "[+] Upgrading pip, setuptools, wheel..."
pip install --upgrade pip setuptools wheel

echo "[+] Installing PyTorch ROCm 5.7 (v2.2.2)..."
pip install torch==2.2.2 torchvision==0.17.2 torchaudio==2.2.2 --index-url https://download.pytorch.org/whl/rocm5.7

echo "[+] Fixing executable stack permissions for ROCm & Torch libraries..."
sudo execstack -c /opt/rocm/lib/*.so* 2>/dev/null || sudo patchelf --clear-execstack /opt/rocm/lib/*.so* 2>/dev/null || true
execstack -c "$VENV_DIR/lib/python3.11/site-packages/torch/lib/"*.so* 2>/dev/null || patchelf --clear-execstack "$VENV_DIR/lib/python3.11/site-packages/torch/lib/"*.so* 2>/dev/null || true

REQ_FILE="requirements-amd.txt"
if [ -f "$REQ_FILE" ]; then
    echo "[+] Installing packages from $REQ_FILE..."
    pip install -r "$REQ_FILE" --prefer-binary --only-binary=scikit-image
else
    echo "[!] Warning: $REQ_FILE not found, skipping requirements installation."
fi

GEN_FILE="$AI_DIR/generative.py"
pkill -f "generative.py" 2>/dev/null
sleep 2

if [ -f "$GEN_FILE" ]; then
    echo "[+] Starting generative.py (Radeon 780M / ROCm GFX1100 / ROCm 5.7)..."
    
    nohup env \
        HSA_OVERRIDE_GFX_VERSION=11.0.0 \
        PYTORCH_ROCM_ARCH=gfx1100 \
        TORCH_BLAS_PREFER_HIPBLASLT=0 \
        USE_HIPBLASLT=0 \
        ROCM_PATH=/opt/rocm \
        HIP_VISIBLE_DEVICES=0 \
        HSA_ENABLE_SDMA=0 \
        AMD_SERIALIZE_KERNEL=3 \
        AMD_SERIALIZE_COPY=3 \
        PYTORCH_CUDA_ALLOC_CONF="garbage_collection_threshold:0.6,max_split_size_mb:64" \
        REDIS_AI_HOST=127.0.0.1 \
        REDIS_AI_PORT=6380 \
        DB_HOST=127.0.0.1 \
        "$VENV_DIR/bin/python" -u "$GEN_FILE" > sd_service.log 2>&1 &
    
    sleep 3
    if pgrep -f "generative.py" > /dev/null; then
        echo "[✓] Native AMD Service started successfully! (Logs: sd_service.log)"
        echo "[*] Run 'tail -f sd_service.log' to monitor output."
    else
        echo "[!] Error: generative.py failed to start. Logs:"
        cat sd_service.log | tail -n 20
        exit 1
    fi
else
    echo "[!] Error: $GEN_FILE not found!"
    exit 1
fi