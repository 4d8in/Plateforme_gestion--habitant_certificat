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
PORT_VALUE=${PORT:-10000}
sed -i "s/{{PORT}}/${PORT_VALUE}/g" /etc/nginx/nginx.conf

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
