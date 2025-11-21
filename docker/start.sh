#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Chạy migrations
echo "📊 Running migrations..."
php artisan migrate --force

# Chạy seeders nếu biến môi trường RUN_SEEDERS được set
if [ "$RUN_SEEDERS" = "true" ] || [ "$RUN_SEEDERS" = "1" ]; then
    echo "🌱 Running seeders..."
    php artisan db:seed --force
    echo "✅ Seeders completed!"
else
    echo "ℹ️  Skipping seeders (set RUN_SEEDERS=true to enable)"
fi

# Start Laravel server
# Render tự động set biến môi trường PORT
PORT=${PORT:-80}
echo "✅ Starting server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT

