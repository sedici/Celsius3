# Despliegue con Docker

Requerimientos:

- GIT
- Docker 18.06+
- Docker compose 1.8+
- Make
- Puertos libres:
    - 80 (Apache)
    - 3306 (MySQL)
    - 9200 (Elasticsearch)
    - 6379 (Redis)

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
sudo make
```

### Dominios

Se deben asignar dominios locales para aquellas instancias a las que desea acceder.
Se debe agregar la siguiente línea por cada dominio por ejemplo el dominio **prebi.localhost** para la instancia local **prebi** asignado a la IP local.

```
127.0.0.1     prebi.localhost
```

Luego se debe asignar ese dominio en la base de datos.

```
sudo docker exec celsius3_mysql mysql --user=celsius3_usr --password=celsius3_pass celsius3 -e "UPDATE instance SET host='prebi.localhost' WHERE url='prebi';"
```

### Acceso

Finalmente se accede al sistema mediante la url asignada.

```
http://prebi.localhost/app_dev.php
```

### Contenedores

Una vez instalados los contenedores con los servicios para Celsius3, para el uso diario se inician y detienen con los siguientes comandos.

```
make up
```

```
make stop
```

