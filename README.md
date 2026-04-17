# SampahRey - Sistem Pengelolaan Sampah (Laravel Fullstack)

Aplikasi fullstack berbasis Laravel untuk kebutuhan tugas pengelolaan sampah dengan dukungan cloud AWS.

## Fitur Utama

- CRUD Laporan Sampah Liar (dengan lokasi, status, dan upload foto)
- CRUD Jadwal Pengangkutan Sampah
- CRUD Monitoring Petugas Kebersihan
- Dashboard ringkasan operasional

## Kesesuaian Ketentuan Tugas

- Jalan di EC2: aplikasi berjalan di container Docker dan diakses via public IP/domain
- Dockerized: tersedia Dockerfile + docker-compose
- RDS: konfigurasi database MySQL via ENV
- S3: upload file laporan ke disk `s3` (via `REPORT_UPLOAD_DISK=s3`)
- GitHub Actions CI/CD: test + deploy ke EC2
- Source code: siap di-host di GitHub

## Struktur Cloud

Lihat diagram lengkap di [docs/architecture.md](docs/architecture.md).

## Setup Lokal

### Prerequisite
- PHP 8.3+
- Composer
- Node.js 18+
- MySQL 5.7+ atau PostgreSQL 12+

### 1. Clone & Install Dependencies

```bash
git clone <repository-url>
cd sampahrey

composer install
npm install
```

### 2. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Konfigurasi Database

#### Untuk MySQL (Recommended)
```env
# .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sampahrey
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

#### Atau untuk PostgreSQL
```env
# .env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sampahrey
DB_USERNAME=postgres
DB_PASSWORD=
```

### 4. Migrate Database

```bash
php artisan migrate
```

### 5. Build Assets

```bash
npm run build
# atau untuk development dengan hot reload:
npm run dev
```

### 6. Jalankan Server

```bash
php artisan serve
```

Akses: **http://127.0.0.1:8000**

### Development Tips

- **Geolocation GPS**: Tombol "📍 Gunakan Lokasi Saya" otomatis capture koordinat dari browser
- **Upload Foto**: Default ke disk `local` (storage/app/public)
- **Database**: Migrasi otomatis membuat tabel waste_reports, pickup_schedules, officers

## Setup Cloud (EC2 + Docker + RDS + S3)

### Prerequisites AWS
- EC2 Instance (Ubuntu 22.04+)
- RDS MySQL 8.0+
- S3 Bucket untuk file uploads
- IAM User dengan permissions untuk EC2, RDS, S3

### 1. Persiapan Instance

```bash
# SSH ke EC2
ssh -i your-key.pem ubuntu@your-ec2-ip

# Update system
sudo apt update && sudo apt upgrade -y

# Install Docker & Docker Compose
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker ubuntu

# Clone repository
git clone <repository-url>
cd sampahrey
```

### 2. Configure Environment untuk RDS + S3

```bash
cp .env.example .env
```

Edit `.env` dengan nilai production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# RDS MySQL Configuration
DB_CONNECTION=mysql
DB_HOST=your-rds-endpoint.rds.amazonaws.com
DB_PORT=3306
DB_DATABASE=sampahrey_prod
DB_USERNAME=admin
DB_PASSWORD=your-secure-password
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci

# S3 Configuration
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=your-bucket-name
REPORT_UPLOAD_DISK=s3

# File Storage
FILESYSTEM_DISK=s3
```

### 3. Generate Application Key

```bash
php artisan key:generate --show
# Copy hasilnya ke .env sebagai APP_KEY
```

### 4. Run Docker Container

```bash
# Build & start
docker compose up -d --build

# Run migrations
docker compose exec app php artisan migrate --force

# Access logs
docker compose logs -f app
```

Aplikasi akan accessible di: `https://your-ec2-ip` atau domain Anda

### 5. Monitoring

```bash
# Check container status
docker compose ps

# View logs
docker compose logs app

# Access shell
docker compose exec app php artisan tinker
```

## CI/CD GitHub Actions

Workflow deploy ada di `.github/workflows/deploy-ec2.yml`.

Tambahkan GitHub Secrets berikut:

- `EC2_HOST`
- `EC2_USERNAME`
- `EC2_SSH_PRIVATE_KEY`
- `EC2_APP_PATH`

Saat push ke branch `main` atau `master`, pipeline akan:

1. install dependency
2. jalankan migration + test
3. SSH ke EC2
4. pull source terbaru
5. rebuild dan restart container Docker

## Endpoint Aplikasi

- `/` dashboard
- `/waste-reports` CRUD laporan sampah
- `/pickup-schedules` CRUD jadwal pengangkutan
- `/officers` CRUD monitoring petugas
