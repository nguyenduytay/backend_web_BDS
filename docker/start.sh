#!/bin/bash
set -e

echo "🚀 Starting Laravel application..." >&2

# Regenerate autoloader để đảm bảo tất cả classes được load đúng
echo "🔄 Regenerating autoloader..." >&2
composer dump-autoload --optimize --no-interaction

# Clear và cache config
echo "📦 Clearing and caching config..." >&2
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ⚠️ XÓA TOÀN BỘ DỮ LIỆU VÀ CHẠY LẠI MIGRATIONS + SEEDERS
# migrate:fresh sẽ:
# - Xóa tất cả các bảng
# - Chạy lại tất cả migrations
# - Chạy seeders (--seed)
echo "🗑️  Resetting database (xóa tất cả dữ liệu cũ)..." >&2
echo "📊 Running fresh migrations and seeders..." >&2
php artisan migrate:fresh --seed --force
echo "✅ Database đã được reset và seed dữ liệu mới!" >&2

# Start Laravel server
# Render tự động set biến môi trường PORT
PORT=${PORT:-80}
echo "✅ Starting server on port $PORT..." >&2
php artisan serve --host=0.0.0.0 --port=$PORT
