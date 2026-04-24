# Optimized Laravel Dockerfile for Render

FROM php:8.2-cli

# Install system packages
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    exif \
    pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working dir
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Permissions
RUN chmod -R 775 storage bootstrap/cache

# Laravel caches
RUN php artisan config:clear || true
RUN php artisan route:clear || true
RUN php artisan view:clear || true

# Expose Render port
EXPOSE 10000

# Start Laravel
CMD php artisan serve --host=0.0.0.0 --port=10000
