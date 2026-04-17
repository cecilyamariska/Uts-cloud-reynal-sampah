#!/bin/bash
# SampahRey Docker - Quick Start Checklist

echo "╔════════════════════════════════════════════════════════════════╗"
echo "║     SampahRey Docker - Quick Start Checklist                  ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}PRE-START CHECKS${NC}"
echo ""
echo "Before running docker-compose up, verify:"
echo ""

# Check 1: .env file
if [ -f .env ]; then
    echo -e "${GREEN}✓${NC} .env file exists"
    if grep -q "DB_HOST=db" .env; then
        echo -e "${GREEN}✓${NC} DB_HOST=db configured"
    else
        echo -e "${RED}✗${NC} DB_HOST not set to 'db' - please update .env"
    fi
else
    echo -e "${RED}✗${NC} .env file missing - run: cp .env.example .env"
fi

echo ""

# Check 2: Docker installation
if command -v docker &> /dev/null; then
    echo -e "${GREEN}✓${NC} Docker installed: $(docker --version)"
else
    echo -e "${RED}✗${NC} Docker not found - please install Docker Desktop"
fi

# Check 3: Docker Compose
if command -v docker-compose &> /dev/null; then
    echo -e "${GREEN}✓${NC} Docker Compose installed: $(docker-compose --version)"
else
    echo -e "${RED}✗${NC} Docker Compose not found - please install"
fi

echo ""
echo -e "${BLUE}DOCKER CONFIGURATION FILES${NC}"
echo ""

# Check files
files=("Dockerfile" "docker-compose.yml" "docker/apache-vhost.conf" "docker/nginx/default.conf" "docker/entrypoint.sh")

for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo -e "${GREEN}✓${NC} $file"
    else
        echo -e "${RED}✗${NC} $file missing"
    fi
done

echo ""
echo -e "${BLUE}READY TO START?${NC}"
echo ""
echo "Run these commands:"
echo ""
echo -e "${YELLOW}1. Build & Start Containers:${NC}"
echo "   docker-compose up -d --build"
echo ""
echo -e "${YELLOW}2. Check Status:${NC}"
echo "   docker-compose ps"
echo ""
echo -e "${YELLOW}3. View Logs:${NC}"
echo "   docker-compose logs -f"
echo ""
echo -e "${YELLOW}4. Test Application:${NC}"
echo "   curl http://localhost"
echo ""
echo -e "${YELLOW}5. Test Database:${NC}"
echo "   docker-compose exec app php artisan tinker"
echo "   >>> DB::connection()->getPdo()"
echo ""
echo -e "${YELLOW}6. Open in Browser:${NC}"
echo "   http://localhost"
echo ""
echo -e "${BLUE}HELP & DOCUMENTATION${NC}"
echo ""
echo "Guides:"
echo "  • DOCKER.md - Complete Docker guide (400+ lines)"
echo "  • DOCKER_COMPLETION_REPORT.md - Verification checklist"
echo ""
echo "Common Issues:"
echo "  • Port 80 in use: Change ports in docker-compose.yml to 8080:80"
echo "  • Database connection refused: Wait 30s for MySQL to start"
echo "  • .env not found: Run: cp .env.example .env"
echo ""
echo "Questions?"
echo "  See: DOCKER.md - Troubleshooting section"
echo ""
