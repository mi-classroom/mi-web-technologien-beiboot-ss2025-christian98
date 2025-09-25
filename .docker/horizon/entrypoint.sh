#!/bin/sh

set -eu

# Cache bootstrap files
php artisan app:deploy --no-interaction

# start horizon
php artisan horizon --verbose
