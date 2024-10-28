dockname := $(shell grep 'name:' docker-compose.yaml | awk '{print $$2}')

.PHONY: all build install deps start stop clean compose/install npm/install database encore tests ps imgs rmi
all: build install
install: start deps
deps: composer/install npm/install

build:
	@docker compose build

start:
	@docker compose up -d

stop:
	@docker compose stop

clean:
	@sudo rm -rf ./node_modules ./package-lock.json ./vendor

composer/install:
	@docker exec --user $(id -u):$(id -g) $(dockname)-php-1 composer install

npm/install:
	@docker exec --user $(id -u):$(id -g) $(dockname)-node-1 npm install

database:
	@docker exec --user $(id -u):$(id -g) $(dockname)-php-1 php bin/console doctrine:database:drop --force
	@docker exec --user $(id -u):$(id -g) $(dockname)-php-1 php bin/console doctrine:database:create
	@docker exec -i $(dockname)-bd-1 sh -c 'exec mysql -ucelsius3_usr -pcelsius3_pass celsius3' < .docker/mysql/celsius3.sql

encore:
	@docker exec --user $(id -u):$(id -g) $(dockname)-php-1 php bin/console assets:install
    @docker exec --user $(id -u):$(id -g) $(dockname)-node-1 yarn run encore dev

tests:
	@docker exec $(dockname)-php-1 php vendor/phpunit/phpunit/phpunit --bootstrap ./tests/bootstrap.php --configuration ./phpunit.xml.dist ./tests

ps:
	@docker ps --filter name=$(dockname)*

imgs:
	@docker images --filter reference=$(dockname)*

rmi:
	@docker rmi -f "$(shell docker images --filter reference=$(dockname)* -q |  tr '\n' ' ')"