FROM php:8.2-fpm

WORKDIR /var/www/html

# Cài dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files để cache
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts

# Copy source code
COPY . .

# Chạy composer scripts sau khi có source code
RUN composer dump-autoload --optimize && \
    php artisan package:discover --ansi

# Quyền
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Port (Render sẽ tự động set PORT env variable)
EXPOSE 80

# Copy và set quyền cho start script
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# CMD chạy Laravel với migrations và seeders
CMD ["/usr/local/bin/start.sh"]
