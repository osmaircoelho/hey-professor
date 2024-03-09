#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo

echo "Composer udpate..."
composer update

echo "Composer install..."
composer install --no-dev --working-dir=/var/www/html

echo "generating application key..."
php artisan key:generate --show

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force 

#echo "Running seed..."
php artisan db:seed --force

echo "Running vite..."
npm install
npm run build


echo "done deploying 🚀"
