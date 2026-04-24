#!/bin/sh

# Run migrations
php artisan migrate --force

# Start Nginx & PHP-FPM
nginx && php-fpm
