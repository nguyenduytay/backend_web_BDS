FROM php:8.1-fpm

WORKDIR /var/www/html

# Cài dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files để cache
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist

# Copy source code
COPY . .

# Dump autoload
RUN composer dump-autoload --optimize

# Quyền
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Port
EXPOSE 80

# CMD chạy Laravel trực tiếp
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=80
