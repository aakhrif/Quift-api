# --- Build stage ---
FROM composer:2 as composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# --- Runtime stage ---
FROM php:8.2-fpm
WORKDIR /var/www

# PHP Extensions
RUN apt-get update && apt-get install -y \
    libzip-dev libonig-dev unzip git \
    && docker-php-ext-install pdo pdo_mysql zip mbstring

# Copy app files
COPY . .

# Copy vendor from build stage
COPY --from=composer /app/vendor ./vendor

# Permissions
RUN chown -R www-data:www-data /var/www
