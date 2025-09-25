FROM php:8.4.10-fpm-alpine as builder

WORKDIR /var/www

# Add Source-Code
COPY bootstrap /var/www/bootstrap
COPY artisan /var/www/artisan
COPY config /var/www/config
COPY lang /var/www/lang
COPY storage /var/www/storage
COPY database /var/www/database
COPY public /var/www/public
COPY routes /var/www/routes
COPY composer.json /var/www/composer.json
COPY composer.lock /var/www/composer.lock
COPY vendor /var/www/vendor
COPY resources /var/www/resources
COPY app /var/www/app

# Set specific permissions
RUN rm -R html \
    && rm -R -f public/ssr \
    && chmod 774 artisan \
    && chmod 664 bootstrap/app.php \
    && chmod 664 public/index.php

FROM registry.gitlab.com/pyramine/laravel-base-image:1.7.6

# Copy custom PHP-FPM config
COPY .docker/php/blog.php-fpm.conf /usr/local/etc/php-fpm.d/blog.php-fpm.conf
COPY .docker/php/php.ini /usr/local/etc/php/php.ini

# Add entrypoints
COPY .docker/cron/entrypoint.sh /var/www/entrypoint-cron.sh
COPY .docker/horizon/entrypoint.sh /var/www/entrypoint-horizon.sh
COPY .docker/php/entrypoint.sh /var/www/entrypoint-php.sh

ARG APP_VERSION="v0.0.0"
ENV APP_VERSION ${APP_VERSION}

COPY .docker/cron/scheduler /etc/cron.d/scheduler
RUN crontab -u www-data /etc/cron.d/scheduler

USER www-data

COPY --from=builder --chown=www-data:www-data /var/www /var/www

RUN php artisan package:discover

HEALTHCHECK --timeout=15s \
  CMD php artisan app:healthcheck
