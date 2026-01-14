# 1. Use an official PHP image with Apache
FROM php:8.2-apache

# 2. Install system dependencies
RUN apt-get update && apt-get install -y \
    libssl-dev \
    unzip \
    git \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# 3. Install and enable PHP extensions
RUN docker-php-ext-install mysqli zip \
    && pecl install mongodb redis \
    && docker-php-ext-enable mongodb redis

# 4. Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 5. Enable Apache mod_rewrite
RUN a2enmod rewrite

# 6. Set the working directory
WORKDIR /var/www/html

# 7. Copy ONLY composer files first
# This ensures dependencies are cached and not overwritten by local folders
COPY composer.json composer.lock* ./

# 8. Install PHP dependencies
# We do this BEFORE copying the rest of the code
RUN composer install --no-interaction --optimize-autoloader --ignore-platform-req=ext-mongodb

# 9. Now copy the rest of your application code
COPY . /var/www/html/

# 10. Set correct permissions for Apache
RUN chown -R www-data:www-data /var/www/html

# 11. Expose port 80
EXPOSE 80

# 12. Start Apache in the foreground
CMD ["apache2-foreground"]