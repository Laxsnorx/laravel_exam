#!/bin/sh
set -e

cp .env.example .env
composer install
php artisan key:generate
php artisan install:api --no-interaction

sed -i "s/^APP_DEBUG=.*/APP_DEBUG=false/" .env

php artisan serve