DOCKNAME := $(shell grep 'name:' docker-compose.yaml | awk '{print $$2}')
ARGS := $(filter-out $(firstword $(MAKECMDGOALS)), $(MAKECMDGOALS))
DB_NAME := celsius3
DB_USERNAME := celsius3_usr
DB_USER_PASS := celsius3_pass

all: docker/build install
install: composer/install npm/install encore # Tiene que estar corriendo para ejecutar esto
postbuild: elastica/populate

clean/all: clean/nmodules clean/pbuild clean/jsonpkgs clean/vendor clean/php-cache


docker/build:
	@docker compose build --no-cache

docker/start:
	@[ "$(ARGS)" = "d" ] && docker compose up -d || docker compose up;

docker/start/d:
	@docker compose up -d

docker/stop:
	@docker compose down


# ------- CLEAN COMMANDS -------

clean/nmodules:
	@sudo rm -rf ./node_modules

clean/pbuild:
	@sudo rm -rf ./public/build

clean/jsonpkgs:
	@sudo rm -rf ./package-lock.json

clean/vendor:
	@sudo rm -rf ./vendor

clean/php-cache:
	@docker exec --user $(id -u):$(id -g) $(DOCKNAME)-php-1 php bin/console cache:clear


# ------- DEPENDENCIES COMMANDS -------

composer/install:
	@docker exec --user $(id -u):$(id -g) $(DOCKNAME)-php-1 composer install

npm/install:
	@docker exec --user $(id -u):$(id -g) $(DOCKNAME)-node-1 npm install

encore:
	@docker exec --user $(id -u):$(id -g) $(DOCKNAME)-php-1 php bin/console assets:install
    @docker exec --user $(id -u):$(id -g) $(DOCKNAME)-node-1 npm run encore dev


# ------- DATABASE COMMANDS -------

db/drop:
	@docker exec --user $(id -u):$(id -g) $(DOCKNAME)-php-1 php bin/console doctrine:database:drop --force

db/import-sql: db/create-user db/create
	@docker exec -i $(DOCKNAME)-db-1 mariadb -u$(DB_USERNAME) -p$(DB_USER_PASS) $(DB_NAME) < '$(ARGS)'
	@echo "Archivo SQL importado en la base de datos '$(DB_NAME)'."

db/check:
	@docker exec --user $(id -u):$(id -g) $(DOCKNAME)-php-1 php bin/console doctrine:schema:update --dump-sql --complete

db/dump:
	@echo "Realizando dump compactado de la base de datos $(DB_NAME) desde el contenedor $(DOCKNAME)-db-1..."
	@docker exec $(DOCKNAME)-db-1 sh -c 'exec mariadb-dump --no-tablespaces --single-transaction --quick --lock-tables=false --compact -u$(DB_USERNAME) -p$(DB_USER_PASS) $(DB_NAME)' | gzip -9 > $(ARGS).gz
	@echo "Dump comprimido completado y guardado en $(ARGS).gz"

db/dump-schema:
	@echo "Realizando dump del esquema de la base de datos $(DB_NAME) desde el contenedor $(DOCKNAME)-db-1..."
	@docker exec $(DOCKNAME)-db-1 sh -c 'exec mariadb-dump --no-data --no-tablespaces --single-transaction --quick --lock-tables=false --compact -u$(DB_USERNAME) -p$(DB_USER_PASS) $(DB_NAME)' | gzip -9 > $(ARGS).gz
	@echo "Dump comprimido completado y guardado en $(ARGS).gz"

# ------- TEST COMMANDS -------

tests:
	@docker exec $(DOCKNAME)-php-1 php vendor/phpunit/phpunit/phpunit --bootstrap ./tests/bootstrap.php --configuration ./phpunit.xml.dist ./tests


# ------- DOCKER COMMANDS -------

ps:
	@docker ps --filter name=$(DOCKNAME)* --format "table {{.Image}}\\t{{.Ports}}\\t{{.Names}}"

imgs:
	@docker images --filter reference=$(DOCKNAME)*

rmi:
	@docker compose down
	@{ \
		if [ -z "$(ARGS)" ]; then \
			docker rmi -f $(shell docker images --filter reference=$(DOCKNAME)* -q | tr '\n' ' '); \
		else \
			docker rmi -f $(DOCKNAME)-$(ARGS):latest; \
		fi \
	}

dx:
	@docker exec -it --user $(id -u):$(id -g) $(DOCKNAME)-$(ARGS)-1 bash


# ------- ELASTICSEARCH COMMANDS -------

elastica/populate:
	@docker exec -it --user $(id -u):$(id -g) $(DOCKNAME)-php-1 php bin/console fos:elastica:populate


# ------- MISCELLANEOUS COMMANDS -------

php/routes:
	@docker exec -it --user $(id -u):$(id -g) $(DOCKNAME)-php-1 php bin/console debug:router $(ARGS)

php/make-migrations:
	@docker exec -it --user $(id -u):$(id -g) $(DOCKNAME)-php-1 php bin/console make:migration

php/migrate:
	@docker exec -it --user $(id -u):$(id -g) $(DOCKNAME)-php-1 php bin/console doctrine:migrations:migrate

# ------- ENVIRONMENT COMMANDS -------

env:
	@./env-handler.sh $(ARGS)

rename:
	@sed -i "s/^name: .*/name: $(ARGS)/" docker-compose.yaml

%:
	@: