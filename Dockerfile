# --- Stage 1: build frontend assets ---
FROM node:20-slim AS assets
WORKDIR /app
COPY package.json ./
RUN npm install
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
RUN npm run build

# --- Stage 2: PHP app ---
FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev libonig-dev unzip git \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring bcmath zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
ENV COMPOSER_NO_AUDIT=1
RUN composer install --no-dev --no-scripts --optimize-autoloader --no-interaction

COPY . .
COPY --from=assets /app/public/build ./public/build

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && composer dump-autoload --optimize

EXPOSE 8080
CMD php artisan migrate --force && php artisan db:seed --force && php artisan serve --host 0.0.0.0 --port ${PORT:-8080}
