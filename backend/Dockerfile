FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    sqlite-dev \
    libsodium-dev \
    oniguruma-dev \
    libzip-dev \
    zip \
    unzip

# Install PHP extensions
# sodium is a shared module in official PHP images — install it, then enable it
RUN docker-php-ext-install pdo_sqlite mbstring zip sodium

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Regenerate autoloader without running artisan hooks (no .env at build time)
RUN composer dump-autoload --optimize --no-interaction --no-scripts

# Create required directories and set permissions.
# passport-keys lives outside the bind-mounted storage so its key files keep
# correct 600 permissions (bind-mounted dirs report 777, which oauth2-server rejects).
RUN mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache passport-keys \
    && touch database/database.sqlite \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache \
    && chmod 700 passport-keys

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["/entrypoint.sh"]
