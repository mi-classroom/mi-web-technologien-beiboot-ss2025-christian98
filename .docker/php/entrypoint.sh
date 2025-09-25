#!/bin/sh

set -eu

# Migrate & Cache bootstrap files
php artisan app:deploy --migrate --no-interaction

# start fpm server
php-fpm
