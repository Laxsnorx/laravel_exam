#!/bin/sh
set -e

cp .env.example .env
composer install
php artisan key:generate

sed -i "s/^APP_DEBUG=.*/APP_DEBUG=false/" .env