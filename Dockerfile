FROM node:20-bullseye AS frontend
WORKDIR /app
COPY package*.json vite.config.js postcss.config.js tailwind.config.js ./
COPY resources resources
RUN npm ci --legacy-peer-deps
RUN npm run build

FROM php:8.3-fpm-bullseye AS backend
ARG DEBIAN_FRONTEND=noninteractive
WORKDIR /var/www

# Dependencies for PHP extensions, zip, gd, postgres
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libzip-dev libpq-dev nginx supervisor \
    && docker-php-ext-install pdo pdo_pgsql zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App files (needed for artisan during composer scripts)
COPY . /var/www

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Copy built assets from frontend stage
COPY --from=frontend /app/public/build /var/www/public/build

# PHP configuration
COPY deploy/render.ini /usr/local/etc/php/conf.d/render.ini

# Nginx and Supervisor configs
COPY deploy/nginx.conf /etc/nginx/nginx.conf
COPY deploy/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY deploy/start.sh /start.sh
RUN chmod +x /start.sh

# Permissions for storage/bootstrap
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

ENV APP_ENV=production \
    PHP_FPM_USER=www-data \
    PHP_FPM_GROUP=www-data

EXPOSE 10000

CMD ["/start.sh"]
