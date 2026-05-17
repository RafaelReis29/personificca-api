FROM php:8.5.2-apache

RUN apt-get update && apt-get install -y \
  libpq-dev \
  && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_pgsql

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copy app files from the app directory.
COPY ./src /var/www/html

USER www-data
