set -e

echo "=============================================="
echo "         CHOOSE YOUR HARDWARE TO RUN"
echo "=============================================="
echo "1) CPU - All (Default)"
echo "2) NVIDIA GPU (Required Nvidia Container Toolkit)"
echo "3) AMD GPU (ROCm / /dev/kfd)"
echo "0) Exit / Cancel"
echo "=============================================="
read -p "Choose your usage for AI service [0-3] (Default: 1): " choice

choice=${choice:-1}

COMPOSE_FILES="-f docker-compose.yml"

case $choice in
    0|[Oo]|[Ee][Xx][Ii][Tt])
        echo "--> Exiting..."
        exit 0
        ;;
    1)
        echo "--> Building for -- CPU"
        COMPOSE_FILES="$COMPOSE_FILES -f docker-compose.cpu.yml"
        ;;
    2)
        echo "--> Building for -- NVIDIA GPU"
        COMPOSE_FILES="$COMPOSE_FILES -f docker-compose.nvidia.yml"
        ;;
    3)
        echo "--> Building for -- AMD GPU"
        COMPOSE_FILES="$COMPOSE_FILES -f docker-compose.amd.yml"
        ;;
    *)
        echo "--> Invalid option, falling back to CPU..."
        COMPOSE_FILES="$COMPOSE_FILES -f docker-compose.cpu.yml"
        ;;
esac

read -p "Rebuild docker file? (y/N): " rebuild
if [[ "$rebuild" =~ ^[Yy]$ ]]; then
    echo "-->  Rebuilding Docker images..."
    docker compose $COMPOSE_FILES build
fi

echo "--> Installing PHP (PHPMailer, Dotenv, Predis)..."
docker compose $COMPOSE_FILES run --rm web_xampp composer install --no-interaction --prefer-dist

echo "--> Running services..."
docker compose $COMPOSE_FILES up -d

echo "=============================================="
echo "                 System Ready"
echo "=============================================="