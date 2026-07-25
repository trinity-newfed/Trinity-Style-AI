#!/bin/bash
# How to setup: open terminal and type "chmod +x manage.sh" then run "./manage.sh"
set -e

NC='\033[0m'              
RED='\033[0;31m'          
GREEN='\033[0;32m'        
YELLOW='\033[0;33m'       
BLUE='\033[0;34m'          
PURPLE='\033[0;35m'        
CYAN='\033[0;36m'          

BYELLOW='\033[1;33m'       
BBLUE='\033[1;34m'         

#Build
run_build() {
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${BBLUE}         CHOOSE YOUR HARDWARE TO RUN          ${NC}"
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${YELLOW}1)${NC} CPU - All (Default)"
    echo -e "${YELLOW}2)${NC} NVIDIA GPU (Required Nvidia Container Toolkit)"
    echo -e "${YELLOW}3)${NC} AMD GPU (ROCm / /dev/kfd)"
    echo -e "${RED}0)${NC} Return to Main Menu"
    echo -e "${BLUE}==============================================${NC}"
    read -p "Choose your usage for AI service [0-3] (Default: 1): " choice

    choice=${choice:-1}
    COMPOSE_FILES="-f docker-compose.yml"

    case $choice in
        0|[Oo]|[Ee][Xx][Ii][Tt])
            echo -e "${RED}--> Returning to main menu...${NC}"
            return 0
            ;;
        1)
            echo -e "${PURPLE}--> Building for -- CPU${NC}"
            COMPOSE_FILES="$COMPOSE_FILES -f docker-compose.cpu.yml"
            ;;
        2)
            echo -e "${PURPLE}--> Building for -- NVIDIA GPU${NC}"
            COMPOSE_FILES="$COMPOSE_FILES -f docker-compose.nvidia.yml"
            ;;
        3)
            echo -e "${PURPLE}--> Building for -- AMD GPU${NC}"
            COMPOSE_FILES="$COMPOSE_FILES -f docker-compose.amd.yml"
            ;;
        *)
            echo -e "${RED}--> Invalid option, falling back to CPU...${NC}"
            COMPOSE_FILES="$COMPOSE_FILES -f docker-compose.cpu.yml"
            ;;
    esac

    read -p "Rebuild docker file? (y/N): " rebuild
    if [[ "$rebuild" =~ ^[Yy]$ ]]; then
        echo -e "${PURPLE}-->  Rebuilding Docker images...${NC}"
        docker compose $COMPOSE_FILES build
    fi

    echo -e "${PURPLE}--> Installing PHP (PHPMailer, Dotenv, Predis)...${NC}"
    docker compose $COMPOSE_FILES run --rm web_xampp composer install --no-interaction --prefer-dist

    echo -e "${PURPLE}--> Running services...${NC}"
    docker compose $COMPOSE_FILES up -d

    echo -e "${BLUE}==============================================${NC}"
    echo -e "${GREEN}                 System Ready                 ${NC}"
    echo -e "${BLUE}==============================================${NC}"
}

#Logs
run_logs() {
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${BBLUE}       VIEW DOCKER LOG FOR 'CONTAINER'?       ${NC}"
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${YELLOW}1)${NC} GENERATIVE AI (${CYAN}trinity_ai_container${NC})"
    echo -e "${YELLOW}2)${NC} LLM AI (${CYAN}trinity_ollama_container${NC})"
    echo -e "${YELLOW}3)${NC} REDIS AI WORKER (${CYAN}redis_ai_service${NC})"
    echo -e "${YELLOW}4)${NC} REDIS MAIL WORKER (${CYAN}redis_mail_service${NC})"
    echo -e "${RED}0)${NC} Return to Main Menu"
    echo -e "${BLUE}==============================================${NC}"
    read -p "Choose what docker logs you want to see [0-4] (Default: 1): " choice

    choice=${choice:-1}
    LOG_FILE=""

    case $choice in
        0|[Oo]|[Ee][Xx][Ii][Tt])
            echo -e "${RED}--> Returning to main menu...${NC}"
            return 0
            ;;
        1)
            echo -e "${PURPLE}--> View logs for GENERATIVE AI${NC}"
            LOG_FILE="trinity_ai_container"
            ;;
        2)
            echo -e "${PURPLE}--> View logs for LLM AI${NC}"
            LOG_FILE="trinity_chat_container"
            ;;
        3)
            echo -e "${PURPLE}--> View logs for REDIS AI WORKER${NC}"
            LOG_FILE="redis_ai_service"
            ;;
        4)
            echo -e "${PURPLE}--> View logs for REDIS MAIL WORKER${NC}"
            LOG_FILE="redis_mail_service"
            ;;
        *)
            echo -e "${PURPLE}--> View logs for GENERATIVE AI${NC}"
            LOG_FILE="trinity_ai_container"
            ;;
    esac

    echo -e "${BLUE}==============================================${NC}"
    echo -e "${RED}        Press Ctrl + C To Out Log View        ${NC}"
    echo -e "${BLUE}==============================================${NC}"
    docker logs -f --tail 100 "$LOG_FILE"
}

# Down
docker_cleanup() {
    echo -e "${CYAN}--> Cleaning up unused Docker resources...${NC}"

    docker system prune -f

    docker builder prune -f
    echo -e "${GREEN}--> Cleanup completed!${NC}"
}

run_down() {
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${BBLUE}                  DOCKER DOWN                 ${NC}"
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${YELLOW}1)${NC} ONLY DOWN"
    echo -e "${YELLOW}2)${NC} DOWN + CLEANUP"
    echo -e "${YELLOW}3)${NC} RESTART DOCKER SERVICE"
    echo -e "${YELLOW}4)${NC} REMOVE ORPHANS + CLEANUP"
    echo -e "${YELLOW}5)${NC} ALL (Down, Orphans, Cleanup & Restart)"
    echo -e "${RED}0)${NC} Return to Main Menu"
    echo -e "${BLUE}==============================================${NC}"
    read -p "Choose command [0-5] (Default: 1): " choice

    choice=${choice:-1}

    case $choice in
        0|[Oo]|[Ee][Xx][Ii][Tt])
            echo -e "${RED}--> Returning to main menu...${NC}"
            return 0
            ;;
        1)
            echo -e "${PURPLE}--> ONLY DOWN${NC}"
            docker compose down
            ;;
        2)
            echo -e "${PURPLE}--> DOWN + CLEANUP${NC}"
            docker compose down
            docker_cleanup
            ;;
        3)
            echo -e "${PURPLE}--> RESTART DOCKER SERVICE${NC}"
            sudo systemctl restart docker
            ;;
        4)
            echo -e "${PURPLE}--> REMOVE ORPHANS + CLEANUP${NC}"
            docker compose down --remove-orphans
            docker_cleanup
            ;;
        5)
            echo -e "${PURPLE}--> ALL${NC}"
            docker compose down --remove-orphans
            docker_cleanup
            sudo systemctl restart docker
            ;;
        *)
            echo -e "${PURPLE}--> ONLY DOWN${NC}"
            docker compose down
            ;;
    esac
    echo -e "${GREEN}--> Done!${NC}"
}

#Main Menu
while true; do
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${BYELLOW}         TRINITY AI - SYSTEM MANAGEMENT       ${NC}"
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${YELLOW}1)${NC} BUILD / RUN Services (Hardware Selection)"
    echo -e "${YELLOW}2)${NC} VIEW Container Logs"
    echo -e "${YELLOW}3)${NC} DOWN / RESTART Docker"
    echo -e "${RED}0)${NC} Exit"
    echo -e "${BLUE}==============================================${NC}"
    read -p "Choose your action [0-3]: " main_choice

    case $main_choice in
        1)
            run_build
            ;;
        2)
            run_logs
            ;;
        3)
            run_down
            ;;
        0|[Oo]|[Ee][Xx][Ii][Tt])
            echo -e "${RED}--> Goodbye!${NC}"
            exit 0
            ;;
        *)
            echo -e "${RED}--> Invalid option. Please try again.${NC}"
            ;;
    esac
    echo ""
done