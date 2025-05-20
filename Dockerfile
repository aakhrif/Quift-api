# --- Build stage: Install dependencies ---
FROM composer:2 AS composer
WORKDIR /app

# Nur Composer-Dateien kopieren und Abhängigkeiten installieren
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# --- Runtime stage: PHP mit FPM ---
FROM php:8.2-fpm

WORKDIR /var/www

# Systemabhängigkeiten und PHP-Extensions installieren
RUN apt-get update && apt-get install -y \
    libzip-dev libonig-dev unzip git \
    && docker-php-ext-install pdo pdo_mysql zip mbstring \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# App-Code kopieren
COPY . .

# Vendor-Ordner aus Build-Stufe übernehmen
COPY --from=composer /app/vendor ./vendor

# Besitzer korrekt setzen (optional, je nach Umgebung)
RUN chown -R www-data:www-data /var/www

# Exponieren ist nicht nötig – handled durch docker-compose
