#!/usr/bin/env bash
set -e

echo "🚀 Starting SampahRey Application..."

# Clear Laravel caches
echo "📝 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Wait for database to be ready
echo "⏳ Waiting for database..."
while ! php artisan tinker --execute="DB::connection()->getPdo()" > /dev/null 2>&1; do
    echo "   Database not ready yet, waiting..."
    sleep 2
done

echo "✅ Database is ready!"

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force --no-interaction

# Optimize application
echo "⚡ Optimizing application..."
php artisan optimize

# Set proper permissions
echo "🔐 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

echo "✨ All set! Starting Apache..."
exec apache2-foreground
