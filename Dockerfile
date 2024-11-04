# Use the official PHP Apache image
FROM php:8.0-apache

# Install necessary PHP extensions (add any additional extensions as needed)
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql

# Set the working directory
WORKDIR /var/www/html

# Copy the existing application directory contents into the container
COPY . .

# Set the document root to the public directory of the Laravel application
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Change Apache's default directory to the Laravel public directory
RUN sed -ri -e 's!^DocumentRoot.*!DocumentRoot ${APACHE_DOCUMENT_ROOT}!' /etc/apache2/sites-available/000-default.conf

# Expose port 80 to the outside world
EXPOSE 80

# Set appropriate permissions (optional, adjust as necessary)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Install Composer (optional, can also be done in the host)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Run Composer install (uncomment if you want to install dependencies in the Docker image)
# RUN composer install --no-dev --optimize-autoloader

# Set the default command to run Apache
CMD ["apache2-foreground"]
