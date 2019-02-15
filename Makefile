.PHONY: build up stop shell prepare dependencies populate install database

default: up

ROOT_DIR ?= /var/www/html/web
PROJECT ?= Celsius3
WEB_CONTAINER ?= celsius3_web
CS ?= \e[1;32m
CE ?= \e[0m

up:
	@echo "$(CS)Starting up containers for $(PROJECT)...$(CE)"
	docker-compose --verbose -d --remove-orphans up

stop:
	@echo "$(CS)Stopping containers for $(PROJECT)...$(CE)"
	@docker-compose stop

shell:
	docker exec -ti $(WEB_CONTAINER) bash

build:
	@echo "$(CS)Building image for $(PROJECT)...$(CE)"
	docker-compose --verbose build

prepare:
	@echo "Creating directories and setting permissions for $(PROJECT)..."
	docker exec $(WEB_CONTAINER) rm -fr web/build/
	docker exec $(WEB_CONTAINER) rm -fr web/bundles/
	docker exec $(WEB_CONTAINER) rm -fr node_modules/
	docker exec $(WEB_CONTAINER) rm -fr vendor/
	docker exec $(WEB_CONTAINER) rm -fr .docker/mysql/
	docker exec $(WEB_CONTAINER) mkdir .docker/mysql
	docker exec $(WEB_CONTAINER) chown $(shell id -u):$(shell id -g) .docker/mysql
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
	curl -XPUT 'http://localhost:9200/app/'
	docker exec --user $(shell id -u):$(shell id -g) $(WEB_CONTAINER) php app/console fos:elastica:populate --no-reset

database:
	@echo "$(CS)Loading database...$(CE)"
	docker exec --user $(shell id -u):$(shell id -g) celsius3_mysql mysql --user=root --password=root -e "DROP DATABASE IF EXISTS celsius3"
	docker exec --user $(shell id -u):$(shell id -g) celsius3_mysql mysql --user=root --password=root -e "CREATE DATABASE IF NOT EXISTS celsius3"
	docker exec --user $(shell id -u):$(shell id -g) celsius3_mysql mysql --user=root --password=root celsius3 < ./.docker/celsius3.sql

install: build up database prepare dependencies populate
