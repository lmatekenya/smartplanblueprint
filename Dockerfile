FROM ubuntu:latest
LABEL authors="lmate"

ENTRYPOINT ["top", "-b"]


# Stage 1: Composer for building dependencies
FROM composer:2.6 AS composer_stage
WORKDIR /app
COPY . .
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --ignore-platform-reqs

# Stage 2: Production web server with PHP-FPM and Nginx
FROM php:8.3-fpm-alpine

# Install Nginx, Supervisor, and other system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev \
    oniguruma-dev \
    curl

# Install and configure PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip \
    gd \
    xml \
    mbstring \
    opcache \
    intl

# Configure Nginx
COPY ./docker/nginx.conf /etc/nginx/nginx.conf

# Configure Supervisor
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create directory for Nginx runtime files
RUN mkdir -p /var/run/nginx /var/run/supervisor

WORKDIR /var/www/html

# Copy built dependencies from the composer stage
COPY --from=composer_stage /app .
COPY --from=composer_stage /app/public public/

# Set correct permissions (www-data is the user in the php-fpm-alpine image)
RUN chown -R www-data:www-data /var/www/html /var/run/nginx

# Expose port 80
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Start Supervisor which will start both Nginx and PHP-FPM
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
