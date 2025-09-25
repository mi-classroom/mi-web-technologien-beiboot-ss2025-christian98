#!/bin/sh

set -eu

# Cache bootstrap files
php artisan app:deploy --no-interaction

# start cron
/usr/sbin/crond -f -l 8 -L /dev/stdout
