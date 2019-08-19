# Despliegue con Docker

Requerimientos:

- GIT
- Docker 18.06+
- Docker compose 1.8+
- Puertos libres:
    - 80 (Apache)
    - 3306 (MySQL)
    - 9200 (Elasticsearch)
    - 6379 (Redis)
    - 8000 (PHPMyAdmin)

### Proyecto

Se clona el proyecto desde su repositorio en GitHub

```
git clone https://github.com/sedici/Celsius3.git
```

### Base de datos

_(Requerido hasta disponer de un dump base en el repositorio)_

Se debe copiar un dump de la base de datos en el directorio **.docker** con el nombre **celsius3.sql**

```
./docker/celsius3.sql
```

### Despliegue

Se ejecuta el despliegue de todos los servicios en docker.

```
docker-compose up
```

### Dominio

Se debe asignar el dominio local para la instancia principal a la que se desea acceder en el archivo de hosts.
En este caso se agrega el dominio **prebi.localhost** en el archivo **/etc/hosts**

```
127.0.0.1     prebi.localhost
```

### Acceso

Finalmente se accede al sistema mediante la url asignada.

```
http://prebi.localhost/app_dev.php
```

También se puede acceder al administrador de la base de datos PHPMyAdmin en la siguiente url.

```
http://localhost:8000/
```

