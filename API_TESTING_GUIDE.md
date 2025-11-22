# Hướng dẫn Test API Tự động

## 🧪 Các cách test API

### Cách 1: PHPUnit Tests (Khuyến nghị cho Development)

#### Chạy tất cả tests:
```bash
php artisan test
```

#### Chạy test cụ thể:
```bash
php artisan test --filter PropertyApiTest
php artisan test --filter AuthApiTest
php artisan test --filter LocationApiTest
```

#### Chạy với coverage:
```bash
php artisan test --coverage
```

### Cách 2: Script PHP (Đơn giản, nhanh)

#### Test local:
```bash
php test-api.php
```

#### Test production:
```bash
php test-api.php https://backend-web-bds.onrender.com
```

### Cách 3: Script Bash (Linux/Mac/Git Bash)

#### Test local:
```bash
chmod +x test-api.sh
./test-api.sh
```

#### Test production:
```bash
./test-api.sh https://backend-web-bds.onrender.com
```

### Cách 4: Postman Collection

1. Import file `postman_collection.json` vào Postman
2. Chạy Collection Runner
3. Xem kết quả

---

## 📋 Test Cases đã tạo

### PropertyApiTest
- ✅ Get all properties
- ✅ Get properties by type
- ✅ Get properties by location
- ✅ Get featured properties
- ✅ Get property detail
- ✅ Create property (requires auth)
- ✅ Update property (requires auth)
- ✅ Delete property (requires auth)

### AuthApiTest
- ✅ User registration
- ✅ User login
- ✅ Get user profile
- ✅ User logout

### LocationApiTest
- ✅ Get all locations
- ✅ Get cities
- ✅ Get districts by city

---

## 🚀 Chạy tests trong CI/CD

### GitHub Actions
Tạo file `.github/workflows/test.yml`:
```yaml
name: API Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - name: Install dependencies
        run: composer install
      - name: Run tests
        run: php artisan test
```

---

## 📝 Lưu ý

1. **PHPUnit Tests**: Cần database test (SQLite in-memory hoặc test database)
2. **Script Tests**: Test trực tiếp trên server (local hoặc production)
3. **Postman**: Cần set environment variables

---

## 🔧 Setup cho PHPUnit Tests

1. Tạo database test trong `.env.testing`:
```env
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

2. Hoặc dùng PostgreSQL test database

3. Chạy migrations cho test:
```bash
php artisan migrate --env=testing
```

---

## 📊 Kết quả

Sau khi chạy tests, bạn sẽ thấy:
- ✅ Số lượng tests passed
- ❌ Số lượng tests failed
- Chi tiết lỗi nếu có

