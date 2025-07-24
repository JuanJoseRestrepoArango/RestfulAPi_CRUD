#!/bin/bash
set -e

echo "Esperando a MySQL en $DB_HOST..."

until mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "SHOW DATABASES;" > /dev/null 2>&1; do
  echo "Esperando a MySQL..."
  sleep 2
done

echo "MySQL está listo"

cd /var/www/html

if [ ! -d "vendor" ]; then
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

chmod -R 775 storage bootstrap/cache

php artisan migrate --force

echo "Iniciando Apache..."
exec apache2-foreground
