FROM php:7.4-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
        zlib1g-dev \
        nano \
        libxml2-dev \
        libzip-dev \
    && docker-php-ext-install \
        pdo_mysql \
        opcache \
        zip

RUN rm -rf /tmp/* /var/cache/apk/*
RUN echo "memory_limit = 512M" >> /usr/local/etc/php/php.ini

WORKDIR /var/www/app
