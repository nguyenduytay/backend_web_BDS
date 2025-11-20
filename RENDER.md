# Hướng dẫn deploy lên Render

## Bước 1: Chuẩn bị

1. Đảm bảo code đã được push lên Git repository (GitHub, GitLab, hoặc Bitbucket)

2. Tạo file `render.yaml` trong root của project (đã có sẵn)

## Bước 2: Tạo dịch vụ trên Render

### Tạo Database (MySQL)

1. Đăng nhập vào [Render Dashboard](https://dashboard.render.com)
2. Click **"New +"** → **"PostgreSQL"** hoặc **"MySQL"**
3. Chọn **"MySQL"**
4. Cấu hình:
   - **Name**: `laravel-db`
   - **Database**: `laravel_db`
   - **User**: `laravel_user`
   - **Region**: Chọn region gần nhất (ví dụ: Singapore)
   - **Plan**: Chọn plan phù hợp (Starter miễn phí)
5. Click **"Create Database"**

### Tạo Web Service

1. Click **"New +"** → **"Web Service"**
2. Kết nối repository của bạn
3. Cấu hình:
   - **Name**: `laravel-backend`
   - **Region**: Cùng region với database
   - **Branch**: `main` (hoặc branch bạn muốn deploy)
   - **Root Directory**: `backend_dev` (nếu project trong subfolder)
   - **Environment**: `Docker`
   - **Dockerfile Path**: `./Dockerfile` hoặc `./Dockerfile.render`
   - **Docker Context**: `.`

### Build & Start Commands

**Build Command:**

```bash
composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

**Start Command:**

```bash
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
```

## Bước 3: Cấu hình Environment Variables

Thêm các biến môi trường sau trong Render Dashboard:

### App Settings

```
APP_NAME=Laravel
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com
LOG_CHANNEL=stack
LOG_LEVEL=error
```

### Database Settings (tự động từ database service)

```
DB_CONNECTION=mysql
DB_HOST=<từ database service>
DB_PORT=<từ database service>
DB_DATABASE=<từ database service>
DB_USERNAME=<từ database service>
DB_PASSWORD=<từ database service>
```

### Cache & Session

```
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local
```

### Broadcast

```
BROADCAST_DRIVER=log
```

### Generate APP_KEY

Chạy lệnh sau trong Shell của Render hoặc local:

```bash
php artisan key:generate --show
```

Copy key và thêm vào `APP_KEY` trong Environment Variables.

## Bước 4: Chạy Migrations và Seeders

Sau khi deploy thành công, chạy migrations:

### Cách 1: Sử dụng Render Shell

1. Vào Web Service → **"Shell"**
2. Chạy:

```bash
php artisan migrate --force
php artisan db:seed --force
```

### Cách 2: Thêm vào Build Command

Cập nhật Build Command:

```bash
composer install --no-dev --optimize-autoloader && php artisan migrate --force && php artisan db:seed --force && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## Bước 5: Kiểm tra

1. Truy cập URL của service: `https://your-app-name.onrender.com`
2. Kiểm tra API endpoints
3. Kiểm tra logs trong Render Dashboard

## Lưu ý quan trọng

### 1. Storage

Render sử dụng ephemeral filesystem, nên files upload sẽ mất khi restart. Cần:

- Sử dụng Cloudinary (đã có trong project)
- Hoặc S3/Spaces cho file storage

### 2. Database Migrations

- Chạy migrations sau mỗi lần deploy có thay đổi database
- Sử dụng `--force` flag trong production

### 3. Caching

- Enable config, route, view caching trong production
- Clear cache khi cần: `php artisan cache:clear`

### 4. Auto-deploy

- Render tự động deploy khi có push mới lên branch đã cấu hình
- Có thể tắt auto-deploy trong settings

### 5. Health Checks

Render tự động check health tại root URL. Đảm bảo route `/` trả về 200 OK.

## Troubleshooting

### Lỗi 502 Bad Gateway

- Kiểm tra logs trong Render Dashboard
- Đảm bảo APP_KEY đã được set
- Kiểm tra database connection

### Lỗi Database Connection

- Kiểm tra DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
- Đảm bảo database service đã running
- Kiểm tra Internal Database URL trong Render

### Lỗi Permission Denied

- Kiểm tra quyền storage và bootstrap/cache
- Thêm vào Build Command:

```bash
chmod -R 755 storage bootstrap/cache
```

### Lỗi Composer

- Kiểm tra composer.json và composer.lock
- Đảm bảo PHP version phù hợp (8.1+)

## Sử dụng render.yaml (Tự động)

Nếu sử dụng `render.yaml`, Render sẽ tự động tạo services:

1. Push code lên repository
2. Trong Render Dashboard → **"New +"** → **"Blueprint"**
3. Kết nối repository
4. Render sẽ tự động tạo services theo `render.yaml`

Sau đó chỉ cần:

- Set `APP_KEY` manually
- Chạy migrations và seeders
