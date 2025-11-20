# Sử dụng PHP 8.1 FPM làm base image
FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www/html

# Cài đặt các dependencies cần thiết
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy file cấu hình
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy composer files trước để cache dependencies
COPY composer.json composer.lock ./

# Cài đặt dependencies (không chạy scripts để tránh lỗi khi chưa có .env)
RUN composer install --no-interaction --no-scripts --prefer-dist

# Copy source code
COPY . .

# Chạy composer scripts sau khi có source code
RUN composer dump-autoload --optimize

# Set quyền
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port
EXPOSE 80

# Start supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

