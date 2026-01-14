# 1. Use an official PHP image with Apache
FROM php:8.2-apache

# 2. Install system dependencies for MongoDB and Redis
RUN apt-get update && apt-get install -y \
    libssl-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# 3. Install and enable PHP extensions
RUN docker-php-ext-install mysqli \
    && pecl install mongodb redis \
    && docker-php-ext-enable mongodb redis

# 4. Enable Apache mod_rewrite (Crucial for .htaccess to work)
RUN a2enmod rewrite

# 5. Set the working directory
WORKDIR /var/www/html

# 6. Copy your application code (This includes the php folder and .htaccess)
COPY . /var/www/html/

# 7. Set correct permissions for Apache
RUN chown -R www-data:www-data /var/www/html

# 8. Expose port 80
EXPOSE 80

# 9. Start Apache in the foreground
CMD ["apache2-foreground"]