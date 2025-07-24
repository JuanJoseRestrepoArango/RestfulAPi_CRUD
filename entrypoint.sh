set -e
cd /var/www/html
if [ ! -d "vendor" ]; then
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

chmod -R 775 storage bootstrap/cache
php artisan migrate --force
apache2-foreground