.PHONY: ALL
all: build install

.PHONY: build
build:
	@docker-compose build

.PHONY: install
install: start deps

.PHONY: deps
deps: composer/install yarn/install

.PHONY: start
start:
	@docker-compose up -d

.PHONY: stop
stop:
	@docker-compose stop

.PHONY: clean
clean:

composer/install:
	@docker-compose exec --user $(id -u):$(id -g) web composer install

yarn/install:
	@docker-compose exec --user $(id -u):$(id -g) web yarn install

database:
	@docker-compose exec --user $(id -u):$(id -g) web php app/console doctrine:database:drop --force
	@docker-compose exec --user $(id -u):$(id -g) web php app/console doctrine:database:create
	@docker exec -i celsius3_mysql sh -c 'exec mysql -ucelsius3_usr -pcelsius3_pass celsius3' < .docker/mysql/celsius3.sql