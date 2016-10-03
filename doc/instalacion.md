Guía de Instalación

En este documento se detallan los pasos para realizar la instalación de Celsius3 en un sistema que cuente con la versión de apache2.4 y php5.5 o superior, ya sea porque viene por default en los repositorios (ubuntu 13.10 o mint 16 en adelante), o porque se está utilizando un ppa.

    Instalar MySQL, apache2, PHP, git y PEAR en el sistema:

    sudo apt-get install lamp-server^ php5-dev php5-cli php-pear php5-sqlite php5-intl git curl php5-gd php5-curl php5-json php5-imagick build-essential libzmq3 php5-zmq libimage-exiftool-perl

    Instalar nodejs, npm y lesscss

    curl -sL https://deb.nodesource.com/setup | sudo bash -
    sudo apt-get install nodejs
    sudo npm -g install less uglify-js uglifycss bower

    Instalar el mecanismo de control de dependencias Composer

    curl -s http://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer

    Hacer un clone del proyecto:

    git clone https://github.com/sedici/Celsius3.git

    Instalar los bundles del proyecto:

    composer install

    Crear el VirtualHost en el archivo /etc/apache2/sites-available/Celsius3.conf con el siguiente contenido (adaptar de acuerdo a la configuración de cada equipo):

    <VirtualHost 127.0.0.1:80>
      ServerName directorio.celsius3.com.localhost
      ServerAlias instancia1.celsius3.com.localhost
      DocumentRoot "/home/usuario/php-workspace/Celsius3/web" 
      DirectoryIndex app.php
      <Directory "/home/usuario/php-workspace/Celsius3/web">
        Options All
        AllowOverride All
        Require all granted
      </Directory>
    </VirtualHost>

    Añadir la entrada correspondiente al archivo /etc/hosts:

    127.0.0.1  directorio.celsius3.com.localhost
    127.0.0.1  instancia1.celsius3.com.localhost

    Habilitar el sitio, el módulo rewrite y reiniciar apache:

    sudo a2ensite Celsius3.conf
    sudo a2enmod rewrite
    sudo service apache2 reload

    Generar la base de datos y el esquema de la base de datos:

    php app/console doctrine:database:create
    php app/console doctrine:schema:create

    Cargar datos de ejemplo en la base de datos (Se debe tener en mysql una base de datos de Celsius2 para extraer paises, ciudades e instituciones, ver parameters.yml):

    php app/console doctrine:fixtures:load --append

    Modificar desde alguna herramienta del estilo de phpMyAdmin las instancias de la tabla instance de la base de datos de Celsius3 para que la columna host coincida con los hosts definidos en el virtualhost.
    Acceder al sitio desde la url directorio.celsius3.com.localhost/app_dev.php

