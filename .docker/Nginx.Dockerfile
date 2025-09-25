FROM nginx:1.29-alpine as builder

WORKDIR /var/www

# Add Source-Code
COPY public /var/www/public

# Configure Nginx
COPY .docker/nginx/nginx.conf /etc/nginx/nginx.conf

# Set specific permissions
RUN rm -R -f .git \
    && rm -R -f public/ssr \
    && find . -type d -exec chmod 0755 {} \; \
    && find . -type f -exec chmod 0644 {} \;

FROM nginx:1.29-alpine

WORKDIR /var/www

ARG APP_VERSION="v0.0.0"
ENV APP_VERSION ${APP_VERSION}

COPY --from=builder /var/www/public /var/www/public
COPY --from=builder /etc/nginx/nginx.conf /etc/nginx/nginx.conf

HEALTHCHECK --timeout=15s \
  CMD wget --no-verbose --tries=1 --spider http://127.0.0.1/health
