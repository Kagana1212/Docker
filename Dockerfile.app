FROM php:8.3-fpm-alpine

RUN apk update && apk add --no-cache \
    mysql-client \
    git \
    unzip \
    libzip-dev \

    && docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN echo "date.timezone=Asia/Tokyo" > /usr/local/etc/php/conf.d/timezone.ini

WORKDIR /var/www/html