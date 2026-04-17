# Configuration Documentation

Panduan lengkap untuk semua konfigurasi aplikasi SampahRey.

## Environment Variables (.env)

### Application Settings

```env
APP_NAME=SampahRey
APP_ENV=local              # local, testing, production
APP_KEY=base64:xxxxx       # Generate dengan: php artisan key:generate
APP_DEBUG=true             # false untuk production
APP_URL=http://localhost   # URL aplikasi
```

| Variable | Default | Description |
|----------|---------|-------------|
| APP_NAME | SampahRey | Nama aplikasi |
| APP_ENV | local | Environment (local/production) |
| APP_DEBUG | true | Debug mode (false untuk production) |
| APP_URL | http://localhost | Base URL aplikasi |
| APP_LOCALE | id | Default locale (id/en) |

### Database Configuration

#### MySQL (Recommended)

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

**Production (AWS RDS):**
```env
DB_CONNECTION=mysql
DB_HOST=sampahrey-db.c9akciq32.ap-southeast-1.rds.amazonaws.com
DB_PORT=3306
DB_DATABASE=sampahrey_prod
DB_USERNAME=admin
DB_PASSWORD=your-secure-password
```

#### PostgreSQL

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sampahrey
DB_USERNAME=postgres
DB_PASSWORD=
```

#### SQLite (Development Only)

```env
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite
```

### Cache Configuration

```env
CACHE_STORE=database      # database, redis, memcached, array, file
# CACHE_PREFIX=            # Optional cache prefix
```

### Session Configuration

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120       # Minutes
SESSION_ENCRYPT=false      # true untuk production
SESSION_PATH=/
SESSION_DOMAIN=null
```

### Queue Configuration

```env
QUEUE_CONNECTION=database  # sync, database, redis, sqs, beanstalkd
```

### File Storage

```env
FILESYSTEM_DISK=local      # local, s3, ftp
REPORT_UPLOAD_DISK=local   # Disk untuk upload foto laporan
```

**S3 Configuration:**
```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=AKIA...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=sampahrey-uploads
AWS_USE_PATH_STYLE_ENDPOINT=false
REPORT_UPLOAD_DISK=s3
```

### Mail Configuration

```env
MAIL_MAILER=log            # smtp, sendmail, mailgun, ses, log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null       # tls, ssl
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Production (AWS SES):**
```env
MAIL_MAILER=ses
MAIL_HOST=email-smtp.ap-southeast-1.amazonaws.com
MAIL_PORT=587
MAIL_USERNAME=your-ses-username
MAIL_PASSWORD=your-ses-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

### Logging

```env
LOG_CHANNEL=stack          # single, daily, slack, syslog, errorlog, stack
LOG_LEVEL=debug            # debug, info, notice, warning, error, critical, alert, emergency
```

---

## Configuration Files

### config/app.php

Application-wide settings:

```php
'name' => env('APP_NAME', 'SampahRey'),
'env' => env('APP_ENV', 'production'),
'debug' => (bool) env('APP_DEBUG', false),
'url' => env('APP_URL', 'http://localhost'),
'timezone' => 'Asia/Jakarta',  // Timezone aplikasi
'locale' => env('APP_LOCALE', 'id'),
'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
'faker_locale' => env('APP_FAKER_LOCALE', 'id_ID'),
```

### config/database.php

Database connections:

```php
'default' => env('DB_CONNECTION', 'mysql'),

'connections' => [
    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'sampahrey'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => env('DB_CHARSET', 'utf8mb4'),
        'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix' => '',
        'strict' => true,
    ],
    // ... other connections
],
```

### config/filesystems.php

Storage disk configuration:

```php
'disks' => [
    'local' => [
        'driver' => 'local',
        'root' => storage_path('app'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'private',
    ],
    
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
    
    's3' => [
        'driver' => 's3',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
        'url' => env('AWS_URL'),
    ],
],
```

### config/cache.php

Caching settings:

```php
'default' => env('CACHE_STORE', 'database'),

'stores' => [
    'database' => [
        'driver' => 'database',
        'connection' => env('CACHE_CONNECTION'),
        'table' => env('CACHE_TABLE', 'cache'),
    ],
    
    'redis' => [
        'driver' => 'redis',
        'connection' => 'cache',
    ],
],
```

---

## Feature Flags / Runtime Configuration

### Per-Request Configuration

```php
// Dalam Controller:
config(['app.debug' => false]); // Override sementara

// Atau:
$value = config('app.name');
config(['app.name' => 'NewName']);
```

---

## Database Schema Configuration

### Migration untuk Waste Reports

```php
Schema::create('waste_reports', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description');
    $table->string('location');
    $table->decimal('latitude', 10, 7)->nullable();
    $table->decimal('longitude', 10, 7)->nullable();
    $table->string('photo_path')->nullable();
    $table->string('photo_disk', 50)->default('local');
    $table->enum('status', ['baru', 'diproses', 'selesai'])->default('baru');
    $table->timestamp('reported_at')->nullable();
    $table->timestamps();
    
    $table->index('status');
    $table->index('reported_at');
});
```

---

## Environment-Specific Configurations

### Development (.env)

```env
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=mysql
CACHE_STORE=database
SESSION_DRIVER=database
FILESYSTEM_DISK=local
```

### Testing (.env.testing)

```env
APP_ENV=testing
APP_DEBUG=true
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
CACHE_STORE=array
```

### Production (.env.production)

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:xxxxx  # Generated securely
DB_CONNECTION=mysql
DB_HOST=rds-endpoint.amazonaws.com
CACHE_STORE=redis
SESSION_ENCRYPT=true
FILESYSTEM_DISK=s3
```

---

## Best Practices

### Security

1. **Never commit .env** - Only commit .env.example
2. **Strong APP_KEY** - Generate dengan: `php artisan key:generate`
3. **Database Passwords** - Use strong passwords (20+ characters)
4. **S3 Credentials** - Use IAM roles instead of hardcoding
5. **Session Encryption** - Enable untuk production: `SESSION_ENCRYPT=true`

### Performance

1. **Cache Configuration** - Use Redis untuk production
2. **Database Optimization** - Add proper indexes
3. **File Storage** - Use S3 untuk production scaling
4. **Log Level** - Set `LOG_LEVEL=warning` untuk production

### Debugging

1. **APP_DEBUG=false** - Always untuk production
2. **Log Errors** - Configure LOG_CHANNEL appropriately
3. **Database Logging** - Enable hanya di development

---

## Verifying Configuration

```bash
# Check current environment
php artisan env

# List all config
php artisan config:list

# Cache config for production
php artisan config:cache

# Clear cached config
php artisan config:clear

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo()
```

