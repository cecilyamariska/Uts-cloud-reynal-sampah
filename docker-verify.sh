#!/bin/bash

# ============================================
# SampahRey Docker Deployment Verification
# ============================================
# This script verifies all Docker setup components

set -e

echo "╔════════════════════════════════════════════════════════════════╗"
echo "║     SampahRey Docker Setup Verification - STEP 2              ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""

# Color definitions
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

PASS=0
FAIL=0

# Function to check status
check_status() {
    local desc=$1
    local result=$2
    
    if [ $result -eq 0 ]; then
        echo -e "${GREEN}✓${NC} $desc"
        ((PASS++))
    else
        echo -e "${RED}✗${NC} $desc"
        ((FAIL++))
    fi
}

echo -e "${BLUE}=== STEP 1: Environment Checks ===${NC}"
echo ""

# Check Docker installation
docker --version > /dev/null 2>&1
check_status "Docker is installed" $?

# Check Docker Compose
docker-compose --version > /dev/null 2>&1
check_status "Docker Compose is installed" $?

# Check .env file exists
[ -f .env ]
check_status ".env file exists" $?

# Check .env has database config
grep -q "DB_HOST=db" .env
check_status ".env configured for Docker (DB_HOST=db)" $?

echo ""
echo -e "${BLUE}=== STEP 2: Configuration Files ===${NC}"
echo ""

# Check Dockerfile
[ -f Dockerfile ]
check_status "Dockerfile exists" $?

# Check docker-compose.yml
[ -f docker-compose.yml ]
check_status "docker-compose.yml exists" $?

# Check Apache config
[ -f docker/apache-vhost.conf ]
check_status "Apache virtual host config exists" $?

# Check Nginx config
[ -f docker/nginx/default.conf ]
check_status "Nginx config exists" $?

# Check entrypoint script
[ -f docker/entrypoint.sh ]
check_status "Entrypoint script exists" $?

# Check .dockerignore
[ -f .dockerignore ]
check_status ".dockerignore exists" $?

echo ""
echo -e "${BLUE}=== STEP 3: Docker Compose Validation ===${NC}"
echo ""

# Validate docker-compose syntax
docker-compose config > /dev/null 2>&1
check_status "docker-compose.yml is valid" $?

echo ""
echo -e "${BLUE}=== STEP 4: Services Configuration ===${NC}"
echo ""

# Check all three services are defined
grep -q "app:" docker-compose.yml
check_status "PHP-Apache 'app' service defined" $?

grep -q "web:" docker-compose.yml
check_status "Nginx 'web' service defined" $?

grep -q "db:" docker-compose.yml
check_status "MySQL 'db' service defined" $?

# Check ports are configured
grep -q "80:80" docker-compose.yml
check_status "Port 80 is exposed" $?

grep -q "3306:3306" docker-compose.yml
check_status "Port 3306 is exposed" $?

echo ""
echo -e "${BLUE}=== STEP 5: Docker Images Check ===${NC}"
echo ""

# Check if images are available locally
docker inspect php:8.3-apache > /dev/null 2>&1
check_status "PHP 8.3-Apache image available" $?

docker inspect nginx:1.24-alpine > /dev/null 2>&1
check_status "Nginx 1.24-Alpine image available" $?

docker inspect mysql:8.0 > /dev/null 2>&1
check_status "MySQL 8.0 image available" $?

echo ""
echo -e "${BLUE}=== STEP 6: Laravel Configuration ===${NC}"
echo ""

# Check composer.json exists
[ -f composer.json ]
check_status "composer.json exists" $?

# Check package.json exists  
[ -f package.json ]
check_status "package.json exists" $?

# Check Laravel app structure
[ -d app/Http/Controllers ]
check_status "Laravel app structure present" $?

# Check database migrations
[ -d database/migrations ]
check_status "Database migrations directory exists" $?

echo ""
echo -e "${BLUE}=== STEP 7: Security Configuration ===${NC}"
echo ""

# Check security headers in Apache config
grep -q "X-Frame-Options" docker/apache-vhost.conf
check_status "Apache security headers configured" $?

# Check security headers in Nginx config
grep -q "X-Frame-Options" docker/nginx/default.conf
check_status "Nginx security headers configured" $?

# Check .env is in .gitignore
grep -q "^\.env$" .gitignore
check_status ".env is in .gitignore" $?

echo ""
echo -e "${BLUE}=== STEP 8: Documentation ===${NC}"
echo ""

# Check documentation files
[ -f DOCKER.md ]
check_status "DOCKER.md documentation exists" $?

[ -f SETUP.md ]
check_status "SETUP.md exists" $?

[ -f CONFIG.md ]
check_status "CONFIG.md exists" $?

[ -f DOCKER_COMPLETION_REPORT.md ]
check_status "DOCKER_COMPLETION_REPORT.md exists" $?

echo ""
echo "╔════════════════════════════════════════════════════════════════╗"
echo "║                    VERIFICATION SUMMARY                        ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""

TOTAL=$((PASS + FAIL))
echo -e "Total Checks: ${TOTAL}"
echo -e "${GREEN}Passed: ${PASS}${NC}"
if [ $FAIL -gt 0 ]; then
    echo -e "${RED}Failed: ${FAIL}${NC}"
fi

echo ""
if [ $FAIL -eq 0 ]; then
    echo -e "${GREEN}╔════════════════════════════════════════════════════════════════╗"
    echo "║          ✓ ALL CHECKS PASSED - READY FOR DEPLOYMENT            ║"
    echo "╚════════════════════════════════════════════════════════════════╝${NC}"
    echo ""
    echo -e "${YELLOW}Next Steps:${NC}"
    echo "1. Start Docker: docker-compose up -d --build"
    echo "2. Check status: docker-compose ps"
    echo "3. Test app: curl http://localhost"
    echo "4. Test DB: docker-compose exec app php artisan tinker"
    echo ""
else
    echo -e "${RED}╔════════════════════════════════════════════════════════════════╗"
    echo "║       ✗ SOME CHECKS FAILED - PLEASE REVIEW ABOVE              ║"
    echo "╚════════════════════════════════════════════════════════════════╝${NC}"
    exit 1
fi
