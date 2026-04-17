# SampahRey Setup Guide

Panduan lengkap untuk setup aplikasi SampahRey di berbagai environment.

## Table of Contents
1. [Local Development](#local-development)
2. [Production (AWS EC2 + RDS + S3)](#production-aws)
3. [Database Configuration](#database-configuration)
4. [Troubleshooting](#troubleshooting)

---

## Local Development

### Requirements
- PHP 8.3 atau lebih baru
- Composer
- Node.js 18+
- MySQL 5.7+ atau PostgreSQL 12+
- Git

### Step-by-Step Installation

#### 1. Clone Repository
```bash
git clone https://github.com/yourusername/sampahrey.git
cd sampahrey
```

#### 2. Install PHP Dependencies
```bash
composer install
```

#### 3. Install Node Dependencies
```bash
npm install
```

#### 4. Setup Environment File
```bash
cp .env.example .env
```

#### 5. Generate Application Key
```bash
php artisan key:generate
```

#### 6. Create Database (Jika Menggunakan MySQL)

**Option A: MySQL via Command Line**
```bash
mysql -u root -p
CREATE DATABASE sampahrey;
EXIT;
```

**Option B: MySQL via GUI (Navicat, MySQL Workbench)**
- Buat database baru dengan nama `sampahrey`
- Character set: `utf8mb4`
- Collation: `utf8mb4_unicode_ci`

#### 7. Configure Database di .env

Edit file `.env` dan sesuaikan:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sampahrey
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

#### 8. Run Database Migrations
```bash
php artisan migrate
```

Output yang diharapkan:
```
Running migrations.

  2026_04_17_000100_create_waste_reports_table ................. 374.71ms DONE
  2026_04_17_000110_create_pickup_schedules_table ............... 27.09ms DONE
  2026_04_17_000120_create_officers_table ....................... 11.46ms DONE
```

#### 9. Build Frontend Assets
```bash
npm run build
```

#### 10. Start Development Server
```bash
php artisan serve
```

Server akan jalan di: **http://127.0.0.1:8000**

### Running in Development Mode (with Hot Reload)

Terminal 1 - PHP Server:
```bash
php artisan serve
```

Terminal 2 - Frontend Watcher:
```bash
npm run dev
```

---

## Production (AWS)

### Architecture Overview
```
┌─────────────────┐
│   GitHub Repo   │
└────────┬────────┘
         │ (Push)
         ▼
┌─────────────────┐
│  GitHub Actions │ (CI/CD)
└────────┬────────┘
         │ (Deploy)
         ▼
┌──────────────────────┐
│  AWS EC2 Instance    │ (Docker Container)
│  - App + PHP Runtime │
└──────┬───────────────┘
       │
  ┌────┴─────┬──────────┐
  ▼          ▼          ▼
┌──────┐  ┌──────┐  ┌──────┐
│ RDS  │  │  S3  │  │CloudFront│
│MySQL │  │Files │  │CDN    │
└──────┘  └──────┘  └──────┘
```

### Prerequisites AWS
- AWS Account dengan billing active
- EC2 key pair dibuat
- Security groups configured
- IAM user dengan permissions

### Step 1: Provision AWS Resources

#### Create RDS MySQL Instance
```bash
# Dari AWS Console:
1. Go to RDS → Databases → Create Database
2. Engine: MySQL 8.0.35
3. Template: Free tier (opsional, untuk production gunakan production)
4. DB Instance Identifier: sampahrey-db
5. Master username: admin
6. Auto generate password: Yes
7. DB Instance class: db.t3.micro
8. Storage: 100 GB
9. VPC Security Group: Buat baru atau gunakan existing
10. Initial database name: sampahrey_prod
11. Create Database

# Note value:
- Endpoint (Host)
- Username
- Password (save di secret manager)
```

#### Create S3 Bucket
```bash
# Dari AWS Console:
1. Go to S3 → Create Bucket
2. Bucket name: sampahrey-uploads-prod-<random>
3. Region: ap-southeast-1
4. Block public access: Yes (Keep private)
5. Create Bucket

# Set CORS Policy:
[
  {
    "AllowedHeaders": ["*"],
    "AllowedMethods": ["GET", "PUT", "POST"],
    "AllowedOrigins": ["https://yourdomain.com"],
    "ExposeHeaders": ["ETag"]
  }
]
```

#### Launch EC2 Instance
```bash
# Dari AWS Console:
1. Go to EC2 → Instances → Launch Instances
2. Name: sampahrey-app
3. AMI: Ubuntu Server 22.04 LTS
4. Instance Type: t3.medium (2GB RAM, 2 vCPU)
5. Key Pair: Select atau create new
6. VPC & Security Group: 
   - Allow SSH (22) from your IP
   - Allow HTTP (80) from anywhere
   - Allow HTTPS (443) from anywhere
7. Storage: 50GB (gp3)
8. Launch Instance

# Note:
- Public IPv4 Address
- Security Group ID
```

### Step 2: Configure EC2 Instance

SSH ke instance:
```bash
ssh -i your-key.pem ubuntu@your-ec2-public-ip
```

Update system:
```bash
sudo apt update
sudo apt upgrade -y
```

Install Docker:
```bash
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker ubuntu
newgrp docker
```

Clone repository:
```bash
git clone https://github.com/yourusername/sampahrey.git
cd sampahrey
```

### Step 3: Setup Environment

Copy production environment:
```bash
cp .env.example .env
```

Edit `.env` dengan AWS credentials:

```bash
nano .env
```

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# RDS Values (dari step 1)
DB_HOST=sampahrey-db.c9akciq32.us-east-1.rds.amazonaws.com
DB_DATABASE=sampahrey_prod
DB_USERNAME=admin
DB_PASSWORD=your-password

# S3 Values (dari IAM User)
AWS_ACCESS_KEY_ID=AKIA...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=sampahrey-uploads-prod-...
```

Generate APP_KEY:
```bash
php artisan key:generate --show
# Copy output ke .env sebagai APP_KEY
```

### Step 4: Run with Docker

Build dan start:
```bash
docker compose up -d --build
```

Run migrations:
```bash
docker compose exec app php artisan migrate --force
```

Check status:
```bash
docker compose ps
docker compose logs app
```

### Step 5: Setup SSL Certificate

Install Certbot:
```bash
sudo apt install certbot python3-certbot-nginx -y
```

Get certificate:
```bash
sudo certbot certonly --standalone -d yourdomain.com
```

### Step 6: Setup Nginx Reverse Proxy

Install Nginx:
```bash
sudo apt install nginx -y
```

Configure:
```bash
sudo nano /etc/nginx/sites-available/sampahrey
```

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;

    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    location / {
        proxy_pass http://localhost:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/sampahrey /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## Database Configuration

### MySQL Setup

#### Create User (Production Best Practice)
```sql
CREATE USER 'sampahrey_user'@'%' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON sampahrey_prod.* TO 'sampahrey_user'@'%';
FLUSH PRIVILEGES;
```

#### Connection Strings

**Development:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sampahrey
DB_USERNAME=root
DB_PASSWORD=
```

**Production (RDS):**
```env
DB_CONNECTION=mysql
DB_HOST=sampahrey-db.xxxxx.rds.amazonaws.com
DB_PORT=3306
DB_DATABASE=sampahrey_prod
DB_USERNAME=sampahrey_user
DB_PASSWORD=strong_password_here
```

### PostgreSQL Setup

#### Development Install (Linux)
```bash
sudo apt install postgresql postgresql-contrib -y
sudo -u postgres createdb sampahrey
sudo -u postgres psql
```

```sql
CREATE USER sampahrey_user WITH PASSWORD 'password';
ALTER ROLE sampahrey_user SET client_encoding TO 'utf8';
ALTER ROLE sampahrey_user SET default_transaction_isolation TO 'read committed';
ALTER ROLE sampahrey_user SET default_transaction_deferrable TO on;
GRANT ALL PRIVILEGES ON DATABASE sampahrey TO sampahrey_user;
\q
```

#### PostgreSQL .env
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sampahrey
DB_USERNAME=sampahrey_user
DB_PASSWORD=password
```

---

## Troubleshooting

### "No such table: waste_reports"

**Solusi:**
```bash
php artisan migrate
```

### "VITE manifest not found"

**Solusi:**
```bash
npm run build
```

### Database Connection Refused

**Check:**
```bash
# MySQL running?
mysql -u root -p -e "SELECT 1"

# RDS Security Group?
# Pastikan EC2 security group allowed

# Credentials correct?
php artisan tinker
>>> DB::connection()->getPdo()
```

### Permission Denied on Storage

**Solusi:**
```bash
sudo chown -R ubuntu:ubuntu storage/
chmod -R 755 storage/
```

### Container won't start

```bash
docker compose logs app
docker compose down
docker compose up -d --build
```

---

## Monitoring & Maintenance

### Check Application Status
```bash
php artisan tinker
>>> DB::table('waste_reports')->count()
```

### View Logs
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Docker logs
docker compose logs -f app

# System logs
sudo journalctl -u docker -f
```

### Database Backup (AWS RDS)
```bash
# Automated: AWS RDS Snapshots (daily)
# Manual:
mysql -h your-rds-endpoint -u admin -p sampahrey_prod | gzip > backup.sql.gz
```

---

## Support & Troubleshooting

Untuk bantuan lebih lanjut:
1. Check [Laravel Documentation](https://laravel.com/docs)
2. Check [AWS Documentation](https://docs.aws.amazon.com/)
3. Create GitHub Issue

