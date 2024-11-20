FROM php:8.1.18-fpm-bullseye

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY php.ini $PHP_INI_DIR/php.ini

RUN apt-get update \
&& apt-get upgrade --yes \
&& apt-get install --yes zip unzip libzip-dev git libicu-dev libcairo2-dev libjpeg-dev libgif-dev default-mysql-client libpq-dev \
&& docker-php-ext-install pdo_mysql pdo_pgsql

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN docker-php-ext-install pdo_mysql mysqli intl zip

EXPOSE 9000
