.PHONY: build up stop shell prepare dependencies populate install database

default: install

ROOT_DIR ?= /var/www/html/web
PROJECT ?= Celsius3
WEB_CONTAINER ?= celsius3_web
CS ?= \e[1;32m
CE ?= \e[0m

up:
	@echo "$(CS)Starting up containers for $(PROJECT)...$(CE)"
	docker-compose up -d --remove-orphans

stop:
	@echo "$(CS)Stopping containers for $(PROJECT)...$(CE)"
	@docker-compose stop

shell:
	docker exec -ti $(WEB_CONTAINER) bash

build:
	@echo "$(CS)Building image for $(PROJECT)...$(CE)"
	docker-compose build

prepare:
	@echo "$(CS)Creating directories and setting permissions for $(PROJECT)...$(CE)"
	docker exec $(WEB_CONTAINER) rm -fr web/build/
	docker exec $(WEB_CONTAINER) rm -fr web/bundles/
	docker exec $(WEB_CONTAINER) rm -fr node_modules/
	docker exec $(WEB_CONTAINER) rm -fr vendor/
	docker exec $(WEB_CONTAINER) mkdir -p /.composer
	docker exec $(WEB_CONTAINER) chown $(shell id -u):$(shell id -g) /.composer
	docker exec $(WEB_CONTAINER) mkdir -p /.yarn
	docker exec $(WEB_CONTAINER) chown $(shell id -u):$(shell id -g) /.yarn
	docker exec $(WEB_CONTAINER) mkdir -p /.cache
	docker exec $(WEB_CONTAINER) chown $(shell id -u):$(shell id -g) /.cache
	docker exec $(WEB_CONTAINER) rm -fr app/cache/*
	docker exec $(WEB_CONTAINER) rm -fr app/logs/*
	docker exec $(WEB_CONTAINER) rm -fr app/spool/*

dependencies:
	@echo "$(CS)Installing yarn dependencies for $(PROJECT)...$(CE)"
	docker exec --user $(shell id -u):$(shell id -g) $(WEB_CONTAINER) yarn install --ignore-optional --force
	@echo "$(CS)Installing composer depencencies for $(PROJECT)...$(CE)"
	docker exec --user $(shell id -u):$(shell id -g) $(WEB_CONTAINER) composer install

populate:
	@echo "$(CS)Populating elasticsearch index for $(PROJECT)...$(CE)"
	docker exec --user $(shell id -u):$(shell id -g) $(WEB_CONTAINER) curl -XPUT "http://localhost:9200/app"
	docker exec --user $(shell id -u):$(shell id -g) $(WEB_CONTAINER) php app/console fos:elastica:populate --no-reset

database:
	@echo "$(CS)Loading database...$(CE)"
	cat ./.docker/celsius3.sql | docker exec --user $(shell id -u):$(shell id -g) -i celsius3_mysql /usr/bin/mysql --user=celsius3_usr --password=celsius3_pass celsius3

schema:
	@echo "$(CS)Generating schema...$(CE)"
	docker exec --user $(shell id -u):$(shell id -g) $(WEB_CONTAINER) php app/console doctrine:schema:update --force

install: build up prepare dependencies database schema populate
