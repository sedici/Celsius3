dockname := $(shell grep 'name:' docker-compose.yaml | awk '{print $$2}')
args := $(filter-out $(firstword $(MAKECMDGOALS)), $(MAKECMDGOALS))
MYSQL_DB := celsius3
MYSQL_USER := celsius3_usr
MYSQL_PASS := celsius3_pass
SQL_FILE := .docker/mysql/celsius3.sql
MYSQL_DUMP_FILE := .docker/mysql/celsius3_dump.sql
MYSQL_DUMP_SCHEMA_FILE := .docker/mysql/celsius3_dump_schema.sql

all: docker/build install
install: composer/install npm/install encore # Tiene que estar corriendo para ejecutar esto
postbuild: elastica/populate

clean/all: clean/nmodules clean/pbuild clean/jsonpkgs clean/vendor clean/php-cache


docker/build:
	@docker compose build --no-cache

docker/start:
	@[ "$(args)" = "d" ] && docker compose up -d || docker compose up;

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
	@docker exec --user $(id -u):$(id -g) $(dockname)-php-1 php bin/console cache:clear


# ------- DEPENDENCIES COMMANDS -------

composer/install:
	@docker exec --user $(id -u):$(id -g) $(dockname)-php-1 composer install

npm/install:
	@docker exec --user $(id -u):$(id -g) $(dockname)-node-1 npm install

encore:
	@docker exec --user $(id -u):$(id -g) $(dockname)-php-1 php bin/console assets:install
    @docker exec --user $(id -u):$(id -g) $(dockname)-node-1 npm run encore dev


# ------- DATABASE COMMANDS -------

db/drop:
	@docker exec --user $(id -u):$(id -g) $(dockname)-php-1 php bin/console doctrine:database:drop --force

db/import-sql: db/create-user db/create
	@docker exec -i $(dockname)-db-1 mysql -u$(MYSQL_USER) -p$(MYSQL_PASS) $(MYSQL_DB) < '$(args)'
	@echo "Archivo SQL '$(SQL_FILE)' importado en la base de datos '$(MYSQL_DB)'."

db/check:
	@docker exec --user $(id -u):$(id -g) $(dockname)-php-1 php bin/console doctrine:schema:update --dump-sql --complete

db/dump:
	@echo "Realizando dump compactado de la base de datos $(MYSQL_DB) desde el contenedor $(dockname)-db-1..."
	@docker exec $(dockname)-db-1 sh -c 'exec mysqldump --no-tablespaces --single-transaction --quick --lock-tables=false --compact -u$(MYSQL_USER) -p$(MYSQL_PASS) $(MYSQL_DB)' | gzip -9 > $(args).gz
	@echo "Dump comprimido completado y guardado en $(MYSQL_DUMP_FILE).gz"

db/dump-schema:
	@echo "Realizando dump del esquema de la base de datos $(MYSQL_DB) desde el contenedor $(dockname)-db-1..."
	@docker exec $(dockname)-db-1 sh -c 'exec mysqldump --no-data --no-tablespaces --single-transaction --quick --lock-tables=false --compact -u$(MYSQL_USER) -p$(MYSQL_PASS) $(MYSQL_DB)' | gzip -9 > $(MYSQL_DUMP_SCHEMA_FILE).gz
	@echo "Dump comprimido completado y guardado en $(MYSQL_DUMP_SCHEMA_FILE).gz"

# ------- TEST COMMANDS -------

tests:
	@docker exec $(dockname)-php-1 php vendor/phpunit/phpunit/phpunit --bootstrap ./tests/bootstrap.php --configuration ./phpunit.xml.dist ./tests


# ------- DOCKER COMMANDS -------

ps:
	@docker ps --filter name=$(dockname)* --format "table {{.Image}}\\t{{.Ports}}\\t{{.Names}}"

imgs:
	@docker images --filter reference=$(dockname)*

rmi:
	@docker compose down
	@{ \
		if [ -z "$(args)" ]; then \
			docker rmi -f $(shell docker images --filter reference=$(dockname)* -q | tr '\n' ' '); \
		else \
			docker rmi -f $(dockname)-$(args):latest; \
		fi \
	}

dx:
	@docker exec -it --user $(id -u):$(id -g) $(dockname)-$(args)-1 bash


# ------- ELASTICSEARCH COMMANDS -------

elastica/populate:
	@docker exec -it --user $(id -u):$(id -g) $(dockname)-php-1 php bin/console fos:elastica:populate


# ------- MISCELLANEOUS COMMANDS -------

php/routes:
	@docker exec -it --user $(id -u):$(id -g) $(dockname)-php-1 php bin/console debug:router $(args)

php/make-migrations:
	@docker exec -it --user $(id -u):$(id -g) $(dockname)-php-1 php bin/console make:migration

php/migrate:
	@docker exec -it --user $(id -u):$(id -g) $(dockname)-php-1 php bin/console doctrine:migrations:migrate

# ------- ENVIRONMENT COMMANDS -------

env:
	@./env-handler.sh $(args)

rename:
	@sed -i "s/^name: .*/name: $(args)/" docker-compose.yaml

%:
	@: