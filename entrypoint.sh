#!/bin/bash
set -e

echo "Configurando el entorno..."
cp .env.example .env

echo "Esperando a MySQL en $DB_HOST..."

until mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "SHOW DATABASES;" > /dev/null 2>&1; do
  echo "Esperando a MySQL..."
  sleep 2
done

echo "MySQL está listo"

  

if ! grep -q "^API_KEY=" .env; then
  echo "Generando API_KEY..."
  API_KEY=$(php -r "echo base64_encode(random_bytes(32));")
  echo "API_KEY=$API_KEY" >> .env
fi

cd /var/www/html

if [ ! -d "vendor" ]; then
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache


php artisan key:generate
echo "Ejecutando migraciones..."

php artisan migrate --force

echo "Creando documentación Swagger..."
php artisan config:clear
php artisan cache:clear
php artisan l5-swagger:generate -vvv
echo "Iniciando Apache..."
exec apache2-foreground
