dockname := $(shell grep 'name:' docker-compose.yaml | awk '{print $$2}')
args := $(filter-out $(firstword $(MAKECMDGOALS)), $(MAKECMDGOALS))

all: build install
install: start/d deps postbuild
deps: composer/install npm/install encore
postbuild: elastica/populate

clean: clean/nmodules clean/pbuild clean/jsonpkgs clean/vendor

build:
	@docker compose build --no-cache

start:
	@[ "$(args)" = "d" ] && docker compose up -d || docker compose up;

start/d:
	@docker compose up -d

stop:
	@docker compose stop

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
    @docker exec --user $(id -u):$(id -g) $(dockname)-node-1 npm run encore dev

tests:
	@docker exec $(dockname)-php-1 php vendor/phpunit/phpunit/phpunit --bootstrap ./tests/bootstrap.php --configuration ./phpunit.xml.dist ./tests

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

elastica/populate:
	@docker exec -it --user $(id -u):$(id -g) $(dockname)-php-1 php bin/console fos:elastica:populate

env:
	@./env-handler.sh $(args)

rename:
	@sed -i "s/^name: .*/name: $(args)/" docker-compose.yaml

%:
	@: