FROM php:7.1-apache

# Se modifica el 'document root'
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
&& sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Se incluye Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Configuración de PHP
RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini \
&& sed -i 's/memory_limit = 128M/memory_limit = 2048M/g' $PHP_INI_DIR/php.ini

# Configuración de apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Se instalan paquetes básicos necesarios
RUN apt-get -y update && apt-get install -y apt-utils gnupg apt-transport-https acl wget make gawk exiftool

# Se agregan repositorios para NodeJS y Yarn
RUN curl -sL https://deb.nodesource.com/setup_11.x | bash - \
&& curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
&& echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list

# Se instalan dependencias del sistema
RUN apt-get -y update \
&& apt-get install -y libzmq3-dev libzip-dev zip unzip libcairo2-dev libjpeg-dev libgif-dev nodejs yarn libicu-dev \
&& docker-php-ext-install zip pdo_mysql intl \
&& pecl install -o -f zmq-beta redis xdebug \
&& rm -fr /tmp/pear \
&& docker-php-ext-enable zmq redis xdebug

# Se limpia la imagen
RUN apt-get clean