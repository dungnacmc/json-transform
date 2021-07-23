FROM php:7.4-fpm

RUN apt-get update -y && apt-get clean

RUN apt-get install -y zlib1g-dev libicu-dev g++ zip unzip ssh libssl-dev && rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY --chown=www-data:www-data . /var/www/html

RUN chgrp -R www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

WORKDIR /var/www/html


