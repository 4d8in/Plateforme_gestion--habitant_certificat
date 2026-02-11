#!/usr/bin/env bash
set -euo pipefail

# Generate key if missing
if [ -z "${APP_KEY:-}" ]; then
  php artisan key:generate --force
fi

# Optimize caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage symlink (ignore errors)
php artisan storage:link || true

# Inject PORT into nginx config (Render provides PORT)
if [ -n "${PORT:-}" ]; then
  envsubst '\$PORT' < /etc/nginx/nginx.conf > /etc/nginx/nginx.conf.out && mv /etc/nginx/nginx.conf.out /etc/nginx/nginx.conf
fi

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
