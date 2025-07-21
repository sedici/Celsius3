FROM php:8.1-fpm-bookworm

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y \
        zip unzip libzip-dev \
        git \
        libicu-dev \
        libcairo2-dev \
        libjpeg-dev \
        libgif-dev \
        default-mysql-client \
        libpq-dev \
        exiftool \
    && docker-php-ext-install pdo_mysql pdo_pgsql

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN docker-php-ext-install pdo_mysql mysqli intl zip

COPY php.ini $PHP_INI_DIR/php.ini

RUN git config --global --add safe.directory /var/www/html

EXPOSE 9000
