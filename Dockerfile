FROM php:7.4-apache

RUN apt-get update && apt-get install -y \
    libmcrypt-dev \
    default-mysql-client \
    libmagickwand-dev --no-install-recommends \
    && pecl install mcrypt \
    && pecl install imagick \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable imagick

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite
