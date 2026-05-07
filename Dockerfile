# Base PHP image with Apache
FROM php:8.2-apache

# Install required system dependencies
RUN apt-get update && apt-get install -y \
  default-mysql-client \
  && docker-php-ext-install pdo pdo_mysql \
  && apt-get clean

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set Apache document root
ENV APACHE_DOCUMENT_ROOT /var/www/html

# Update Apache config to use custom document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
  /etc/apache2/sites-available/*.conf \
  /etc/apache2/apache2.conf \
  /etc/apache2/conf-available/*.conf

# Copy all files to container
COPY . /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Railway uses PORT env variable dynamically
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf

# Expose Railway port
EXPOSE 8080

# Start Apache
CMD ["apache2-foreground"]