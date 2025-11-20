# Hướng dẫn Deploy - Tóm tắt nhanh

## 🐳 Chạy với Docker (Local)

### Quick Start

```bash
# 1. Tạo file .env
cp .env.example .env

# 2. Cập nhật .env với thông tin database:
DB_HOST=mysql
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_password

# 3. Build và chạy
docker-compose up -d --build

# 4. Cài đặt và migrate
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed

# 5. Truy cập: http://localhost:8080
```

Xem chi tiết trong [DOCKER.md](./DOCKER.md)

## ☁️ Deploy lên Render

### Quick Start

1. Push code lên GitHub/GitLab
2. Tạo MySQL Database trên Render
3. Tạo Web Service với Docker
4. Cấu hình Environment Variables
5. Chạy migrations và seeders

Xem chi tiết trong [RENDER.md](./RENDER.md)

### Sử dụng render.yaml (Tự động)

1. Push code có file `render.yaml`
2. Tạo Blueprint trên Render
3. Render tự động tạo services
4. Set `APP_KEY` và chạy migrations

## 📝 Checklist trước khi deploy

### Local (Docker)

- [ ] File `.env` đã được tạo và cấu hình
- [ ] Docker và Docker Compose đã cài đặt
- [ ] Port 8080 và 3306 chưa được sử dụng

### Render

- [ ] Code đã push lên Git repository
- [ ] Database service đã được tạo
- [ ] Environment variables đã được set
- [ ] `APP_KEY` đã được generate
- [ ] Migrations đã chạy
- [ ] Seeders đã chạy (nếu cần)

## 🔧 Troubleshooting

### Docker

- **Port đã được sử dụng**: Đổi port trong `docker-compose.yml`
- **Permission denied**: Chạy `chmod -R 755 storage bootstrap/cache`
- **Database connection error**: Kiểm tra DB_HOST trong `.env`

### Render

- **502 Bad Gateway**: Kiểm tra logs, đảm bảo APP_KEY đã set
- **Database connection**: Kiểm tra Internal Database URL
- **Build failed**: Kiểm tra composer.json và PHP version

## 📚 Tài liệu chi tiết

- [DOCKER.md](./DOCKER.md) - Hướng dẫn chi tiết Docker
- [RENDER.md](./RENDER.md) - Hướng dẫn chi tiết Render
