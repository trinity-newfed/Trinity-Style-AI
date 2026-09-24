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
GRAY='\033[0;90m'

BYELLOW='\033[1;33m'       
BBLUE='\033[1;34m'         

IS_AMD=false

# Helper to select hardware profile
select_hardware() {
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${BBLUE}         CHOOSE YOUR HARDWARE PROFILE         ${NC}"
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${YELLOW}1)${NC} CPU - All (Default)"
    echo -e "${YELLOW}2)${NC} NVIDIA GPU (Required Nvidia Container Toolkit)"
    echo -e "${YELLOW}3)${NC} AMD GPU (ROCm Host Service + Docker CPU)"
    echo -e "${RED}0)${NC} Return to Main Menu"
    echo -e "${BLUE}==============================================${NC}"
    read -p "Choose hardware profile [0-3] (Default: 1): " hw_choice

    hw_choice=${hw_choice:-1}
    COMPOSE_FILES="-f docker-compose.yml"
    IS_AMD=false

    case $hw_choice in
        0|[Oo]|[Ee][Xx][Ii][Tt])
            return 1
            ;;
        1)
            COMPOSE_FILES="$COMPOSE_FILES -f docker-compose.cpu.yml"
            ;;
        2)
            COMPOSE_FILES="$COMPOSE_FILES -f docker-compose.nvidia.yml"
            ;;
        3)
            COMPOSE_FILES="$COMPOSE_FILES -f docker-compose.amd.yml"
            IS_AMD=true
            echo -e "${PURPLE}--> Selected AMD Profile (ROCm Host Service + Docker CPU)${NC}"
            ;;
        *)
            echo -e "${RED}--> Invalid option, falling back to CPU...${NC}"
            COMPOSE_FILES="$COMPOSE_FILES -f docker-compose.cpu.yml"
            ;;
    esac
    return 0
}

# Sub-menu
select_container_target() {
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${YELLOW}1)${NC} Database (trinity_db)"
    echo -e "${YELLOW}2)${NC} Mail Redis (trinity_redis_mail)"
    echo -e "${YELLOW}3)${NC} Mail Worker (worker_mail)"
    echo -e "${YELLOW}4)${NC} Ollama AI (trinity_ollama)"
    echo -e "${YELLOW}5)${NC} AI Redis (trinity_redis_ai)"
    if [ "$IS_AMD" = false ]; then
        echo -e "${YELLOW}6)${NC} AI Backend (ai_backend)"
    fi
    echo -e "${YELLOW}7)${NC} Web XAMPP (web_xampp)"
    echo -e "${YELLOW}8)${NC} AI Worker (worker_ai)"
    echo -e "${BLUE}==============================================${NC}"
    read -p "Select container target: " c_choice

    case $c_choice in
        1) TARGET="trinity_db" ;;
        2) TARGET="trinity_redis_mail" ;;
        3) TARGET="worker_mail" ;;
        4) TARGET="trinity_ollama" ;;
        5) TARGET="trinity_redis_ai" ;;
        6) 
            if [ "$IS_AMD" = true ]; then
                echo -e "${RED}--> AI Backend is running natively on Host for AMD profile.${NC}"
                return 1
            fi
            TARGET="ai_backend" 
            ;;
        7) TARGET="web_xampp" ;;
        8) TARGET="worker_ai" ;;
        *) 
            echo -e "${RED}--> Invalid container selected.${NC}"
            return 1
            ;;
    esac
    return 0
}

# Build
run_build() {
    if ! select_hardware; then
        echo -e "${RED}--> Returning to main menu...${NC}"
        return 0
    fi

    read -p "Rebuild docker images? (y/N): " rebuild
    if [[ "$rebuild" =~ ^[Yy]$ ]]; then
        echo -e "${PURPLE}--> Rebuilding Docker images...${NC}"
        docker compose $COMPOSE_FILES build
    fi

    echo -e "${PURPLE}--> Installing PHP dependencies...${NC}"
    docker compose $COMPOSE_FILES run --rm web_xampp composer install --no-interaction --prefer-dist

    echo -e "${PURPLE}--> Running services...${NC}"
    docker compose $COMPOSE_FILES up -d

    echo -e "${PURPLE}--> Waiting for MySQL to be ready...${NC}"
    until docker exec trinity_mysql_container mysqladmin ping -h localhost -uroot -proot_password --silent; do
        sleep 2
    done

    echo -e "${PURPLE}--> Granting SELECT privileges to chat-agent-support...${NC}"
    docker exec -i trinity_mysql_container mysql -u root -proot_password <<EOF
CREATE USER IF NOT EXISTS 'chat-agent-support'@'%' IDENTIFIED BY 'chatagent123';
GRANT SELECT ON tf_database.* TO 'chat-agent-support'@'%';
FLUSH PRIVILEGES;
EOF
    echo -e "${GREEN}--> Granted privileges successfully!${NC}"

    if [ "$IS_AMD" = true ]; then
        echo -e "${PURPLE}--> Initializing AMD Native Host Environment (amd.sh)...${NC}"
        if [ -f "./amd.sh" ]; then
            chmod +x ./amd.sh
            ./amd.sh
        else
            echo -e "${RED}--> Error: ./amd.sh script not found!${NC}"
        fi
    fi

    echo -e "${BLUE}==============================================${NC}"
    echo -e "${GREEN}                 System Ready                 ${NC}"
    echo -e "${BLUE}==============================================${NC}"
}

# Restart Services
run_restart() {
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${BBLUE}              RESTART SERVICES                ${NC}"
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${YELLOW}1)${NC} Restart ALL Services"
    echo -e "${YELLOW}2)${NC} Restart Specific Container"
    echo -e "${RED}0)${NC} Return to Main Menu"
    echo -e "${BLUE}==============================================${NC}"
    read -p "Choose restart option [0-2] (Default: 1): " choice

    choice=${choice:-1}

    case $choice in
        0|[Oo]|[Ee][Xx][Ii][Tt])
            echo -e "${RED}--> Returning to main menu...${NC}"
            return 0
            ;;
        1)
            if select_hardware; then
                echo -e "${PURPLE}--> Restarting all Docker services...${NC}"
                docker compose $COMPOSE_FILES restart
            fi
            ;;
        2)
            if ! select_hardware; then
                echo -e "${RED}--> Returning to main menu...${NC}"
                return 0
            fi

            if select_container_target; then
                echo -e "${PURPLE}--> Restarting service: ${TARGET}...${NC}"
                docker compose $COMPOSE_FILES restart "$TARGET"
            fi
            ;;
        *)
            echo -e "${RED}--> Invalid option.${NC}"
            return 0
            ;;
    esac
    echo -e "${GREEN}--> Restart completed!${NC}"
}

# Force Recreate
run_recreate() {
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${BBLUE}          RECREATE CONTAINER (FORCE)          ${NC}"
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${YELLOW}1)${NC} Recreate ALL Services"
    echo -e "${YELLOW}2)${NC} Recreate Specific Service"
    echo -e "${RED}0)${NC} Return to Main Menu"
    echo -e "${BLUE}==============================================${NC}"
    read -p "Choose option [0-2] (Default: 1): " choice

    choice=${choice:-1}

    case $choice in
        0|[Oo]|[Ee][Xx][Ii][Tt])
            echo -e "${RED}--> Returning to main menu...${NC}"
            return 0
            ;;
        1)
            if select_hardware; then
                echo -e "${PURPLE}--> Force recreating ALL Docker services...${NC}"
                docker compose $COMPOSE_FILES up -d --force-recreate
            fi
            ;;
        2)
            if ! select_hardware; then
                echo -e "${RED}--> Returning to main menu...${NC}"
                return 0
            fi

            if select_container_target; then
                echo -e "${PURPLE}--> Force recreating service: ${TARGET}...${NC}"
                docker compose $COMPOSE_FILES up -d --force-recreate "$TARGET"
            fi
            ;;
        *)
            echo -e "${RED}--> Invalid option.${NC}"
            return 0
            ;;
    esac
    echo -e "${GREEN}--> Recreate completed!${NC}"
}

# Logs
run_logs() {
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${BBLUE}       VIEW DOCKER LOG FOR CONTAINER          ${NC}"
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${YELLOW}1)${NC} GENERATIVE AI (${CYAN}trinity_ai_container${NC})"
    echo -e "${YELLOW}2)${NC} LLM AI (${CYAN}trinity_chat_container${NC})"
    echo -e "${YELLOW}3)${NC} REDIS AI WORKER (${CYAN}redis_ai_service${NC})"
    echo -e "${YELLOW}4)${NC} REDIS MAIL WORKER (${CYAN}redis_mail_service${NC})"
    echo -e "${YELLOW}5)${NC} AMD HOST SD1.5 LOG (${CYAN}sd_service.log${NC})"
    echo -e "${RED}0)${NC} Return to Main Menu"
    echo -e "${BLUE}==============================================${NC}"
    read -p "Choose log option [0-5] (Default: 1): " choice

    choice=${choice:-1}

    case $choice in
        0|[Oo]|[Ee][Xx][Ii][Tt])
            echo -e "${RED}--> Returning to main menu...${NC}"
            return 0
            ;;
        1) docker logs -f --tail 100 "trinity_ai_container" ;;
        2) docker logs -f --tail 100 "trinity_chat_container" ;;
        3) docker logs -f --tail 100 "redis_ai_service" ;;
        4) docker logs -f --tail 100 "redis_mail_service" ;;
        5) 
            if [ -f "./sd_service.log" ]; then
                tail -f -n 100 ./sd_service.log
            else
                echo -e "${RED}--> Log file ./sd_service.log not found.${NC}"
            fi
            ;;
        *) docker logs -f --tail 100 "trinity_ai_container" ;;
    esac
}

# Cleanup
docker_cleanup() {
    echo -e "${CYAN}--> Cleaning up unused Docker resources...${NC}"
    docker system prune -f
    docker builder prune -f
    echo -e "${GREEN}--> Cleanup completed!${NC}"
}

# Down
run_down() {
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${BBLUE}                  DOCKER DOWN                 ${NC}"
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${YELLOW}1)${NC} ONLY DOWN"
    echo -e "${YELLOW}2)${NC} DOWN + CLEANUP"
    echo -e "${YELLOW}3)${NC} RESTART DOCKER DAEMON"
    echo -e "${YELLOW}4)${NC} REMOVE ORPHANS + CLEANUP"
    echo -e "${YELLOW}5)${NC} ALL (Down, Orphans, Cleanup & Kill AMD Host Process)"
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
            echo -e "${PURPLE}--> Stopping services...${NC}"
            docker compose down
            ;;
        2)
            echo -e "${PURPLE}--> Stopping services & cleaning up...${NC}"
            docker compose down
            docker_cleanup
            ;;
        3)
            echo -e "${PURPLE}--> Restarting Docker Daemon...${NC}"
            sudo systemctl restart docker
            ;;
        4)
            echo -e "${PURPLE}--> Removing orphans & cleaning up...${NC}"
            docker compose down --remove-orphans
            docker_cleanup
            ;;
        5)
            echo -e "${PURPLE}--> Executing full tear-down...${NC}"
            docker compose down --remove-orphans
            docker_cleanup
            sudo systemctl restart docker
            
            if pgrep -f "generative.py" > /dev/null; then
                echo -e "${PURPLE}--> Stopping AMD Native Host Service (generative.py)...${NC}"
                pkill -f "generative.py"
            fi
            ;;
        *)
            docker compose down
            ;;
    esac
    echo -e "${GREEN}--> Done!${NC}"
}

# Main Menu
while true; do
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${BYELLOW}         TRINITY AI - SYSTEM MANAGEMENT       ${NC}"
    echo -e "${BLUE}==============================================${NC}"
    echo -e "${YELLOW}1)${NC} BUILD / RUN       ${GRAY}(Build image & start containers)${NC}"
    echo -e "${YELLOW}2)${NC} RESTART           ${GRAY}(Fast restart container process)${NC}"
    echo -e "${YELLOW}3)${NC} RECREATE          ${GRAY}(Force recreate containers/config)${NC}"
    echo -e "${YELLOW}4)${NC} LOGS              ${GRAY}(View container logs)${NC}"
    echo -e "${YELLOW}5)${NC} DOWN              ${GRAY}(Stop and remove containers)${NC}"
    echo -e "${RED}0)${NC} Exit"
    echo -e "${BLUE}==============================================${NC}"
    read -p "Choose action [0-5]: " main_choice

    case $main_choice in
        1) run_build ;;
        2) run_restart ;;
        3) run_recreate ;;
        4) run_logs ;;
        5) run_down ;;
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