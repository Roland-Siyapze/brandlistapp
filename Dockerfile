FROM php:8.2-apache
WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libzip-dev libicu-dev libpq-dev \
    && docker-php-ext-install pdo_mysql gd bcmath \
    && a2enmod rewrite

# Change Apache document root to /public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application code
COPY . /var/www/html
RUN composer install --no-dev --prefer-dist --no-scripts --no-progress

RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
CMD ["apache2-foreground"]