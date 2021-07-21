FROM php:7.4-fpm

RUN apt-get update -y && apt-get clean

RUN apt-get install -y zlib1g-dev libicu-dev g++ zip unzip ssh libssl-dev && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Install addition php ext
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && pecl install redis && docker-php-ext-enable redis


COPY --chown=www-data:www-data . /var/www/html


RUN composer install --prefer-dist --no-ansi --no-interaction --no-progress --no-scripts --no-dev --optimize-autoloader

WORKDIR /var/www/html


