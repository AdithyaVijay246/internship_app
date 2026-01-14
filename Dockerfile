# 1. Use an official PHP image with Apache
FROM php:8.2-apache

# 2. Install system dependencies for MongoDB and Redis
RUN apt-get update && apt-get install -y \
    libssl-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# 3. Install and enable PHP extensions
# mysqli: for MySQL
# mongodb: for MongoDB
# redis: for Redis
RUN docker-php-ext-install mysqli \
    && pecl install mongodb redis \
    && docker-php-ext-enable mongodb redis

# 4. Enable Apache mod_rewrite (useful for clean URLs)
RUN a2enmod rewrite

# 5. Set the working directory in the container
WORKDIR /var/www/html

# 6. Copy only composer files first (optimizes caching)
# If you use composer, uncomment the lines below:
# COPY composer.json composer.lock* ./
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
#     && composer install --no-scripts --no-autoloader

# 7. Copy the rest of your application code
COPY . /var/www/html/

# 8. Set correct permissions for Apache
RUN chown -R www-data:www-data /var/www/html

# 9. Expose port 80 for the web server
EXPOSE 80

# 10. Start Apache in the foreground
CMD ["apache2ctl", "-D", "FOREGROUND"]