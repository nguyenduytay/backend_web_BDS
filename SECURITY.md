# Security Guidelines

## Tổng quan bảo mật

Dự án này đã được cải thiện với các biện pháp bảo mật toàn diện phù hợp cho doanh nghiệp lớn.

## Các biện pháp bảo mật đã triển khai

### 1. Authentication & Authorization

-   ✅ Laravel Sanctum cho API authentication
-   ✅ Role-based access control (RBAC)
-   ✅ Token expiration (2 giờ)
-   ✅ Token limit per user (5 tokens)
-   ✅ Strong password validation
-   ✅ Rate limiting cho authentication endpoints

### 2. Input Validation & Sanitization

-   ✅ Form Request validation
-   ✅ Custom validation rules
-   ✅ SQL injection protection (Eloquent ORM)
-   ✅ XSS protection

### 3. Security Headers

-   ✅ X-Content-Type-Options: nosniff
-   ✅ X-Frame-Options: DENY
-   ✅ X-XSS-Protection: 1; mode=block
-   ✅ Referrer-Policy: strict-origin-when-cross-origin
-   ✅ Content Security Policy (CSP)
-   ✅ HSTS (production only)

### 4. CORS Configuration

-   ✅ Restricted origins (không phải wildcard)
-   ✅ Specific allowed headers
-   ✅ Credentials support
-   ✅ Preflight caching

### 5. Rate Limiting

-   ✅ Global API rate limiting (100 req/min)
-   ✅ Auth endpoints rate limiting
-   ✅ Custom rate limiting middleware
-   ✅ IP-based and user-based limiting

### 6. Logging & Monitoring

-   ✅ Security event logging
-   ✅ Failed authentication logging
-   ✅ Admin action logging
-   ✅ Suspicious activity detection

### 7. Error Handling

-   ✅ Generic error messages (không leak thông tin)
-   ✅ Proper HTTP status codes
-   ✅ Error logging

## Cấu hình môi trường

### Development

```env
APP_ENV=local
APP_DEBUG=true
FRONTEND_URL=http://localhost:3000
ADMIN_URL=http://localhost:3001
```

### Production

```env
APP_ENV=production
APP_DEBUG=false
FRONTEND_URL=https://yourdomain.com
ADMIN_URL=https://admin.yourdomain.com
```

## Best Practices

### 1. Password Security

-   Mật khẩu tối thiểu 8 ký tự
-   Phải chứa chữ hoa, chữ thường, số và ký tự đặc biệt
-   Không chứa thông tin cá nhân
-   Không có chuỗi lặp lại

### 2. Token Management

-   Token hết hạn sau 2 giờ
-   Tối đa 5 tokens per user
-   Automatic cleanup của tokens cũ
-   Secure token storage

### 3. API Security

-   Tất cả API endpoints đều có rate limiting
-   Authentication required cho sensitive endpoints
-   Proper HTTP methods cho CRUD operations
-   Input validation cho tất cả requests

### 4. Database Security

-   Sử dụng Eloquent ORM (SQL injection protection)
-   Proper indexing
-   Soft deletes cho data recovery
-   Regular backups

## Monitoring & Alerts

### Security Events được log:

-   Failed login attempts
-   Admin actions
-   Suspicious activities
-   Rate limit violations
-   Token creation/deletion

### Log Locations:

-   `storage/logs/laravel.log` - General logs
-   `storage/logs/security.log` - Security events (nếu cấu hình)

## Deployment Security

### 1. Server Configuration

-   HTTPS only trong production
-   Proper SSL/TLS configuration
-   Firewall configuration
-   Regular security updates

### 2. Environment Variables

-   Không commit sensitive data
-   Sử dụng .env files
-   Proper .env.example template
-   Environment-specific configurations

### 3. Database Security

-   Strong database passwords
-   Limited database user permissions
-   Regular security patches
-   Encrypted connections

## Incident Response

### 1. Security Breach

1. Immediately revoke all tokens
2. Check logs for suspicious activities
3. Notify affected users
4. Update security measures
5. Document incident

### 2. Rate Limit Violations

1. Check logs for patterns
2. Adjust rate limits if needed
3. Block malicious IPs if necessary
4. Monitor for improvements

## Regular Security Tasks

### Weekly

-   Review security logs
-   Check for failed authentication attempts
-   Monitor rate limit violations
-   Update dependencies

### Monthly

-   Security audit
-   Review user permissions
-   Check token usage patterns
-   Update security configurations

### Quarterly

-   Penetration testing
-   Security training
-   Policy review
-   Disaster recovery testing

## Contact

Nếu phát hiện lỗ hổng bảo mật, vui lòng liên hệ:

-   Email: security@yourcompany.com
-   Phone: +84-xxx-xxx-xxx

## Changelog

### v1.0.0 (2024-01-XX)

-   Initial security implementation
-   Basic authentication & authorization
-   Rate limiting
-   Security headers
-   Input validation
-   Logging & monitoring
