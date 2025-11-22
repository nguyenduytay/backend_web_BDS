# Hướng dẫn sửa lỗi CORS

## 🔍 Nguyên nhân lỗi CORS

Lỗi CORS xảy ra khi:
1. Frontend gọi API từ domain khác (cross-origin)
2. Backend không cho phép origin của frontend trong `allowed_origins`
3. Content Security Policy (CSP) quá chặt chẽ

## ✅ Đã sửa

### 1. Cấu hình CORS (`config/cors.php`)
- ✅ Đã thêm patterns cho các platform phổ biến (Render, Vercel, Netlify, Railway)
- ✅ Hỗ trợ environment variable `CORS_ALLOWED_ORIGINS`
- ✅ Cho phép localhost với mọi port

### 2. Content Security Policy
- ✅ Không áp dụng CSP cho API routes (`api/*`)
- ✅ CSP chỉ áp dụng cho web routes

## 🚀 Cách sửa lỗi CORS

### Cách 1: Thêm domain frontend vào .env (Khuyến nghị)

Thêm vào file `.env`:

```env
FRONTEND_URL=https://your-frontend-domain.com
ADMIN_URL=https://your-admin-domain.com
```

Hoặc thêm nhiều origins:

```env
CORS_ALLOWED_ORIGINS=https://frontend.com,https://admin.com,http://localhost:3000
```

### Cách 2: Cho phép tất cả origins (Chỉ cho development)

Nếu đang development và muốn test nhanh, sửa `config/cors.php`:

```php
'allowed_origins' => ['*'], // Chỉ dùng trong development!
```

**⚠️ CẢNH BÁO:** Không dùng `['*']` trong production!

### Cách 3: Thêm domain cụ thể vào config

Sửa `config/cors.php`:

```php
'allowed_origins' => [
    'https://your-frontend-domain.com',
    'https://your-admin-domain.com',
    // ... các domain khác
],
```

## 🔧 Kiểm tra CORS

### 1. Test với cURL

```bash
curl -H "Origin: https://your-frontend.com" \
     -H "Access-Control-Request-Method: POST" \
     -H "Access-Control-Request-Headers: Content-Type" \
     -X OPTIONS \
     https://backend-web-bds.onrender.com/api/auth/login \
     -v
```

Phải thấy header:
```
Access-Control-Allow-Origin: https://your-frontend.com
Access-Control-Allow-Methods: POST, GET, OPTIONS
Access-Control-Allow-Headers: Content-Type, Authorization
```

### 2. Test trong Browser Console

```javascript
fetch('https://backend-web-bds.onrender.com/api/properties/all', {
  method: 'GET',
  headers: {
    'Content-Type': 'application/json',
  }
})
.then(res => res.json())
.then(data => console.log(data))
.catch(err => console.error('CORS Error:', err));
```

## 📝 Cấu hình cho Render

Thêm vào Environment Variables trong Render Dashboard:

```
FRONTEND_URL=https://your-frontend-domain.com
CORS_ALLOWED_ORIGINS=https://frontend1.com,https://frontend2.com
```

## 🐛 Troubleshooting

### Lỗi: "Access-Control-Allow-Origin header is missing"

**Nguyên nhân:** Origin của bạn không có trong `allowed_origins`

**Giải pháp:**
1. Kiểm tra origin của frontend (xem trong Network tab)
2. Thêm origin vào `allowed_origins` hoặc `allowed_origins_patterns`
3. Clear cache: `php artisan config:clear`

### Lỗi: "Preflight request doesn't pass"

**Nguyên nhân:** OPTIONS request bị chặn

**Giải pháp:**
1. Đảm bảo `allowed_methods` có `OPTIONS`
2. Kiểm tra middleware CORS đã được thêm vào Kernel

### Lỗi: "Credentials flag is true but Access-Control-Allow-Credentials is missing"

**Nguyên nhân:** Frontend gửi `credentials: 'include'` nhưng backend không cho phép

**Giải pháp:**
1. Đảm bảo `supports_credentials` = `true` trong `config/cors.php`
2. Không dùng `allowed_origins: ['*']` khi `supports_credentials: true`

## ✅ Checklist

- [ ] Origin của frontend đã được thêm vào `allowed_origins`
- [ ] `allowed_methods` có `*` hoặc chứa method bạn dùng
- [ ] `allowed_headers` có các headers bạn cần (Authorization, Content-Type)
- [ ] `supports_credentials` = `true` nếu frontend gửi credentials
- [ ] Đã clear config cache: `php artisan config:clear`
- [ ] Đã restart server sau khi sửa config

## 🎯 Ví dụ cấu hình hoàn chỉnh

### Development (.env)
```env
FRONTEND_URL=http://localhost:3000
ADMIN_URL=http://localhost:3001
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:3001,http://127.0.0.1:3000
```

### Production (.env trên Render)
```env
FRONTEND_URL=https://your-frontend.com
ADMIN_URL=https://admin.your-frontend.com
CORS_ALLOWED_ORIGINS=https://your-frontend.com,https://admin.your-frontend.com
```

## 📚 Tham khảo

- Laravel CORS: https://laravel.com/docs/cors
- MDN CORS: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS

