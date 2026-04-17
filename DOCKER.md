# Docker Setup untuk SampahRey

Panduan lengkap untuk menjalankan aplikasi SampahRey menggunakan Docker.

## Prerequisites

- Docker Desktop (Windows/Mac) atau Docker Engine (Linux)
- Docker Compose
- Git

## Quick Start (5 menit)

### 1. Clone Repository
```bash
git clone https://github.com/yourusername/sampahrey.git
cd sampahrey
```

### 2. Setup Environment
```bash
cp .env.example .env
```

Pastikan `.env` sudah configured untuk Docker:
```env
DB_HOST=db
DB_DATABASE=sampahrey
DB_USERNAME=laravel
DB_PASSWORD=laravel
```

### 3. Start Containers
```bash
docker-compose up -d --build
```

**Output yang diharapkan:**
```
✓ sampahrey-app created
✓ sampahrey-nginx created  
✓ sampahrey-db created
```

### 4. Test Aplikasi

Buka browser:
```
http://localhost
```

Seharusnya muncul dashboard SampahRey! 🎉

## Verifikasi Database

Jalankan migrations:
```bash
docker-compose exec app php artisan migrate --force
```

Cek database connection:
```bash
docker-compose exec app php artisan tinker
>>> DB::table('waste_reports')->count()
=> 0
```

---

## Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Docker Network                          │
│                    (sampahrey)                             │
└─────────────────────────────────────────────────────────────┘
         ↑                    ↑                    ↑
         │                    │                    │
    ┌────────┐           ┌────────┐          ┌────────┐
    │  Nginx │───────→   │ Apache │ ←────→ │ MySQL  │
    │ (port  │  proxy    │  PHP   │ PDO    │ (3306) │
    │  80)   │           │ (app)  │        │        │
    └────────┘           └────────┘        └────────┘
   (sampahrey-           (sampahrey-       (sampahrey-
     nginx)                app)              db)
```

### Services:

| Service | Image | Port | Role |
|---------|-------|------|------|
| **app** | PHP 8.3 + Apache | 80 (internal) | Main Laravel app |
| **web** | Nginx 1.24 | 80, 443 | Reverse proxy |
| **db** | MySQL 8.0 | 3306 | Database |

---

## Common Docker Commands

### Check Container Status
```bash
docker-compose ps
```

Output:
```
NAME                COMMAND             STATUS
sampahrey-app       apache2-foreground  Up 2 minutes
sampahrey-nginx     nginx -g daemon off Up 2 minutes
sampahrey-db        mysqld              Up 2 minutes
```

### View Logs
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f web
docker-compose logs -f db
```

### Execute Commands in Container
```bash
# Run Artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan tinker
docker-compose exec app php artisan route:list

# Run npm commands
docker-compose exec app npm run build

# Access bash shell
docker-compose exec app bash
```

### Database Access

#### From Container
```bash
docker-compose exec db mysql -u laravel -p sampahrey
# Enter password: laravel
```

#### From Local Machine (if port forwarded)
```bash
mysql -h 127.0.0.1 -P 3306 -u laravel -p sampahrey
# Enter password: laravel
```

### Stop & Start
```bash
# Stop all containers
docker-compose down

# Stop and remove volumes (clean slate)
docker-compose down -v

# Stop specific service
docker-compose stop app

# Restart all
docker-compose restart

# Restart specific service
docker-compose restart app
```

### Rebuild Images
```bash
# Rebuild after code changes
docker-compose up -d --build

# Force rebuild without cache
docker-compose build --no-cache
docker-compose up -d
```

---

## Troubleshooting

### ❌ "connection refused" - MySQL

**Solusi:**
```bash
# Check if db service is running
docker-compose ps db

# If not running, start it
docker-compose start db

# Check logs
docker-compose logs db

# Wait for healthy status
docker-compose exec db mysqladmin ping -h localhost
```

### ❌ "ERROR: port 80 already in use"

**Solusi:**
Ubah port di `docker-compose.yml`:
```yaml
web:
  ports:
    - "8080:80"  # Use 8080 instead
```

Akses: `http://localhost:8080`

### ❌ "permission denied" - Storage

**Solusi:**
```bash
docker-compose exec app chmod -R 777 storage bootstrap/cache
```

### ❌ "database connection error"

**Solusi:**
```bash
# Check if db container is healthy
docker-compose exec db mysqladmin ping -h localhost

# Restart db
docker-compose restart db

# Wait a moment and test again
docker-compose exec app php artisan tinker
>>> DB::connection()->getPdo()
```

### ❌ "VITE manifest not found"

**Solusi:**
```bash
docker-compose exec app npm run build
```

### ❌ ".env not found" error

**Solusi:**
```bash
cp .env.example .env
docker-compose rebuild
docker-compose up -d --build
```

---

## Environment Variables untuk Docker

Edit `.env` untuk Docker:

```env
# Application
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database (Docker MySQL)
DB_CONNECTION=mysql
DB_HOST=db                 # Service name dalam docker-compose
DB_PORT=3306
DB_DATABASE=sampahrey
DB_USERNAME=laravel
DB_PASSWORD=laravel
DB_ROOT_PASSWORD=root

# File Storage
FILESYSTEM_DISK=local
REPORT_UPLOAD_DISK=local

# Cache
CACHE_STORE=database

# Session
SESSION_DRIVER=database
```

---

## Production Deployment

Untuk production dengan AWS EC2 + RDS:

### 1. Update docker-compose.yml

```yaml
app:
  environment:
    - DB_HOST=your-rds-endpoint.amazonaws.com
    - DB_USERNAME=admin
    - DB_PASSWORD=secure-password
    - FILESYSTEM_DISK=s3
    - AWS_BUCKET=your-bucket
```

### 2. Update .env

```env
APP_ENV=production
APP_DEBUG=false
DB_HOST=your-rds-endpoint.ap-southeast-1.rds.amazonaws.com
DB_USERNAME=admin
DB_PASSWORD=secure-password
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
```

### 3. Build & Push to ECR (AWS Elastic Container Registry)

```bash
aws ecr get-login-password --region ap-southeast-1 | docker login --username AWS --password-stdin <account-id>.dkr.ecr.ap-southeast-1.amazonaws.com

docker build -t sampahrey:latest .

docker tag sampahrey:latest <account-id>.dkr.ecr.ap-southeast-1.amazonaws.com/sampahrey:latest

docker push <account-id>.dkr.ecr.ap-southeast-1.amazonaws.com/sampahrey:latest
```

### 4. Deploy ke EC2

```bash
# SSH to EC2
ssh -i key.pem ubuntu@your-ec2-ip

# Pull latest image
docker pull <account-id>.dkr.ecr.ap-southeast-1.amazonaws.com/sampahrey:latest

# Start with production .env
docker-compose -f docker-compose.prod.yml up -d
```

---

## Performance Tips

### 1. Use Docker Volumes Efficiently
```yaml
volumes:
  - ./:/var/www/html
  - /var/www/html/node_modules      # Exclude node_modules
  - /var/www/html/vendor            # Exclude vendor
```

### 2. Optimize Dockerfile
```dockerfile
# Multi-stage build (already implemented)
FROM composer:2 AS composer_deps
# ... only copy necessary files
```

### 3. Use .dockerignore
```
.git
node_modules
vendor
storage/logs
.env
```

### 4. Cache Layers
```bash
# Don't rebuild if no code changes
docker-compose up -d
# vs
docker-compose up -d --build
```

---

## Security Best Practices

### 1. Use Secret Management
```bash
# Store sensitive data in Docker secrets or environment files
# Never hardcode credentials
```

### 2. Non-root User
```dockerfile
# Run as www-data (not root)
USER www-data
```

### 3. Security Headers
```
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
```

### 4. Network Isolation
```yaml
networks:
  sampahrey:
    driver: bridge
    # Only sampahrey services can communicate
```

---

## Monitoring & Debugging

### Check Container Resource Usage
```bash
docker stats
```

### View All Processes
```bash
docker-compose ps
```

### Inspect Container
```bash
docker inspect sampahrey-app
```

### Real-time Logs with Timestamps
```bash
docker-compose logs -f --timestamps
```

---

## Development Workflow

### 1. Start Development
```bash
docker-compose up -d
```

### 2. Code Changes (Hot Reload)
```bash
# Changes automatically reflect in container
# (mounted volumes)
```

### 3. Database Migrations
```bash
docker-compose exec app php artisan make:migration
docker-compose exec app php artisan migrate
```

### 4. Build Frontend
```bash
docker-compose exec app npm run build
# or for development with hot reload
docker-compose exec app npm run dev
```

### 5. Run Tests
```bash
docker-compose exec app php artisan test
```

### 6. Stop Development
```bash
docker-compose down
```

---

## File Structure

```
sampahrey/
├── Dockerfile                 # Main application image
├── docker-compose.yml         # Orchestration
├── docker/
│   ├── apache-vhost.conf     # Apache virtual host
│   ├── nginx/
│   │   └── default.conf      # Nginx config
│   └── entrypoint.sh         # Container startup script
├── .dockerignore             # Files to exclude from image
├── .env                      # Local environment
├── .env.example              # Template
└── ... (Laravel files)
```

---

## Next Steps

1. ✅ Docker setup complete
2. 📝 CI/CD Pipeline (GitHub Actions)
3. 🚀 Deploy to AWS EC2
4. 📊 Monitoring & Logging
5. 🔐 SSL/TLS Configuration

