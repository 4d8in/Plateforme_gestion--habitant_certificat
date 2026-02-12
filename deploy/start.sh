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

# Safety: warn if vite manifest missing
if [ ! -f /var/www/public/build/manifest.json ]; then
  echo "WARNING: public/build/manifest.json not found. Assets may be missing." >&2
  ls -la /var/www/public || true
fi

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
