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

### Despliegue

Se ejecuta el despliegue de todos los servicios en docker.

```
docker-compose up -d
```
Dado que esta tarea toma algún tiempo en segundo plano hasta completarse, puede tener un seguimiento de los archivos de logs.
```
docker-compose logs -f
```

### Dominio

Se deben crear en el archivo de hosts (/etc/hosts) los dominios locales de las instancias de prueba existentes en la base de datos.
```
127.0.0.1     instancia-a.localhost
127.0.0.1     instancia-b.localhost
127.0.0.1     instancia-c.localhost
```

### Acceso

Finalmente se accede a cada instancia mediante las url asignadas.

```
http://instancia-a.localhost/app_dev.php
http://instancia-b.localhost/app_dev.php
http://instancia-c.localhost/app_dev.php
```
Utilizando las credenciales de acceso correspondientes.

| Instancia     | Admin / Clave   | Usuario / Clave |
| ------------- | :-------------: | :-------------: |
| instancia-a   | admina / admina | usera / usera   |
| instancia-b   | adminb / adminb | userb / userb   |
| instancia-c   | adminc / adminc | userc / userc   |

También se puede acceder al administrador de la base de datos PHPMyAdmin en la siguiente url.

```
http://localhost:8000/
```

