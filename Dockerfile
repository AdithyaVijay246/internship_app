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

# 4. Install Composer (The missing piece)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 5. Enable Apache mod_rewrite
RUN a2enmod rewrite

# 6. Set the working directory
WORKDIR /var/www/html

# 7. Copy application code
COPY . /var/www/html/

# 8. Install PHP dependencies via Composer
# This creates the 'vendor/autoload.php' file your code is looking for
RUN composer install --no-interaction --optimize-autoloader

# 9. Set correct permissions
RUN chown -R www-data:www-data /var/www/html

# 10. Expose port 80
EXPOSE 80

# 11. Start Apache in the foreground
CMD ["apache2-foreground"]