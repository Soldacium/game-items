FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    linux-headers \
    libpq

# Install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pgsql \
        gd \
        zip \
        intl \
        opcache \
        pdo \
        pdo_pgsql

# Configure PHP
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Create directory for custom PHP configuration
RUN mkdir -p /usr/local/etc/php/custom.d \
    && mkdir -p /var/log/php \
    && chown www-data:www-data /var/log/php

# Add custom PHP configuration
COPY docker/php/custom.ini /usr/local/etc/php/custom.d/custom.ini

# Set working directory
WORKDIR /app

# Create volume
VOLUME ["/app"]