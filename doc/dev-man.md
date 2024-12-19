# Manual de uso de Celsius3 para desarrolladores

## General

- La administración del proyecto se hace enteramente mediante la herramienta Make (gestión de imágenes de docker, gestión de contenedores docker, instalación de dependencias, acceso a contenedores en ejecución, etc).

- Para una adecuada administración del clon del repositorio, Make obtiene el atributo `name` presente en el `docker-compose.yaml` para que este clon se administre de forma independiente a cualquier otro conjunto de imágenes y contenedores presentes en el sistema.

    - **Como primer paso** edite el atributo `name` en el `docker-compose.yaml` para que sea propio de este clon.

    - Las imágenes y contenedores de cada servicio del proyecto se referencian mediante el nombre del servicio (por ejemplo, las imágenes y contenedores del servicio de php ser referencian a Make mediante `php`).


## Uso de Make

### Primer paso (es importante hacer esto antes de cualquier otra configuración)

- `make rename <DOCKER_GROUP_NAME>`: renombra el grupo de imágenes y contenedores para este clon del repositorio asignando `DOCKER_GROUP_NAME` al atributo `name` del `docker-compose.yaml`.

    - Es necesario hacerlo como primer paso para que este clon tenga un nombre propio y no genere conflictos con otras clonaciones de este mismo proyecto.

### Comandos varios

- `make [all]`: levanta el proyecto completo y lo pone en funcionamiento. Los contenedores se ejecutan por defecto en segundo plano.
    ¿Qué hace `make [all]`?

    1. `make build`: construye las imágenes de los servicios docker.

    2. `make start`: inicia los contenedores de los servicios docker.

    3. `make composer/install`: instala las dependencias de composer.

    4. `make npm/install`: instala las dependencias de node.

    5. `make encore`: procesa los recursos con webpack encore.

    6. `make elastica/populate`: indexa recursos de la base de datos para accederlos mediante elasticsearch.

- `make imgs`: lista las imágenes docker para este clon del repositorio.

- `make ps`: lista los contenedores docker para este clon del repositorio.

- `make dx <SRV_NAME>`: inicia una terminal sobre el servicio **en ejecución** `SRV_NAME`.

- `make rmi [<SRV_NAME>]`: elimina todas las imágenes del proyecto o, en caso de estar especificada, elimina solo la imagen del servicio con nombre `SRV_NAME`. Si los contenedores están en ejecución primero los para y luego elimina las imágenes que correspondan.

- `make start [d]`: inicia los contenedores. Si `log` está presente se inician los contenedores en primer plano, sino se ejecutan en segundo plano. En caso de que las imágenes no existan las crea, **pero no instala las dependencias necesarias para su correcto funcionamiento**.

- `make clean`: elimina todos archivos generados para la ejecución de este clon del repositorio. Tenga en cuenta que **no para contenedores ni elimina imágenes**.

- `make env <ENV_FILE> <OPT> [<ARGS...>]`: administra las variables de entorno presentes en el archivo `<ENV_FILE>`. Permite listar, agregar, eliminar y comentar variables de entorno. Use `make env` para ver la ayuda.

- `make dxenv <SRV_NAME> <ENV_FILE> <OPT> [<ARGS...>]`: administra las variables de entorno del archivo `<ENV_FILE>` desde dentro del servicio `<SRV_NAME>`.

### Herramientas

- El script bash `env-handler.sh` es una herramienta para manipular variables de entorno de los diferentes programas/sistemas/herramientas del sistema.