# Hướng dẫn chạy dự án với Docker

## Yêu cầu

- Docker
- Docker Compose

## Cài đặt và chạy

### 1. Clone repository và vào thư mục dự án

```bash
cd backend_dev
```

### 2. Tạo file .env từ .env.example (nếu chưa có)

```bash
cp .env.example .env
```

### 3. Cấu hình .env cho Docker

Cập nhật các biến môi trường trong file `.env`:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_password
```

### 4. Build và chạy containers

```bash
# Build images
docker-compose build

# Chạy containers
docker-compose up -d

# Xem logs
docker-compose logs -f
```

### 5. Cài đặt dependencies và chạy migrations

```bash
# Vào container
docker-compose exec app bash

# Trong container, chạy:
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
```

Hoặc chạy từ bên ngoài:

```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

### 6. Truy cập ứng dụng

- Web: http://localhost:8080
- MySQL: localhost:3306
  - Username: laravel_user
  - Password: laravel_password
  - Database: laravel_db

## Các lệnh hữu ích

### Dừng containers

```bash
docker-compose down
```

### Dừng và xóa volumes (xóa database)

```bash
docker-compose down -v
```

### Rebuild containers

```bash
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Xem logs

```bash
# Tất cả services
docker-compose logs -f

# Chỉ app
docker-compose logs -f app

# Chỉ mysql
docker-compose logs -f mysql
```

### Chạy artisan commands

```bash
docker-compose exec app php artisan [command]
```

### Chạy composer

```bash
docker-compose exec app composer [command]
```

### Vào container

```bash
docker-compose exec app bash
```

## Troubleshooting

### Lỗi quyền truy cập storage

```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Xóa cache

```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

### Reset database

```bash
docker-compose exec app php artisan migrate:fresh --seed
```
