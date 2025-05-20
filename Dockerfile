# --- Build stage: Install dependencies ---
FROM composer:2 AS composer
WORKDIR /app

# Nur Composer-Dateien kopieren und Abhängigkeiten installieren
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader \
 && echo "✅ Composer dependencies installed" \
 && ls -la /app/vendor

# --- Runtime stage: PHP mit FPM ---
FROM php:8.2-fpm

WORKDIR /var/www

# Systemabhängigkeiten und PHP-Extensions installieren
RUN apt-get update && apt-get install -y \
    libzip-dev libonig-dev unzip git \
    && docker-php-ext-install pdo pdo_mysql zip mbstring \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && echo "✅ PHP extensions installed"

# App-Code kopieren
COPY . .
RUN echo "📁 Projektstruktur nach COPY .:" && ls -la /var/www

# Vendor-Ordner aus Build-Stufe übernehmen
COPY --from=composer /app/vendor ./vendor
RUN echo "📦 Vendor-Verzeichnis nach COPY:" && ls -la /var/www/vendor

# Besitzer korrekt setzen (optional)
RUN chown -R www-data:www-data /var/www \
 && echo "👤 Besitzer gesetzt auf www-data"

# Exponieren nicht nötig – handled durch docker
