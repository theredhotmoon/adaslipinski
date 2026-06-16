#!/bin/sh
set -e

cd /var/www

# Laravel requires .env to exist even if all values come from Docker env vars
touch .env

# Run composer post-install scripts (package:discover etc.)
composer run-script post-autoload-dump 2>/dev/null || true

# Run migrations
php artisan migrate --force --no-interaction

# Symlink public/storage -> storage/app/public so uploaded media (CMS images)
# is web-accessible at /storage/...
php artisan storage:link --force --no-interaction 2>/dev/null || true

# Generate Passport keys into /var/www/passport-keys (configured via AppServiceProvider).
# --force ensures valid 600-permission keys exist on every fresh container.
php artisan passport:keys --force --no-interaction 2>/dev/null || true
# Keys are written as root here, but php-fpm runs as www-data — hand them over so
# the worker can read the private key (kept at 600, which oauth2-server accepts).
chown -R www-data:www-data /var/www/passport-keys

# Ensure a personal access client exists — login's createToken() needs one, and the
# DB starts without it. Guarded so container restarts don't pile up duplicate clients.
CLIENTS="$(php artisan tinker --execute='echo \Laravel\Passport\Client::count();' 2>/dev/null | tr -cd '0-9')"
if [ -z "$CLIENTS" ] || [ "$CLIENTS" = "0" ]; then
  php artisan passport:client --personal --no-interaction --name="Personal Access Client" 2>/dev/null || true
fi

# Cache config and routes for performance
php artisan config:cache --no-interaction 2>/dev/null || true
php artisan route:cache --no-interaction 2>/dev/null || true

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
