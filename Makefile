.PHONY: up stop shell prepare install

default: up

ROOT_DIR ?= /var/www/html/web
PROJECT ?= Celsius3
WEB_CONTAINER ?= celsius3_web

up:
	@echo "Starting up containers for $(PROJECT)..."
	docker-compose build
	docker-compose pull
	docker-compose up -d --remove-orphans

stop:
	@echo "Stopping containers for $(PROJECT)..."
	@docker-compose stop

shell:
	docker exec -ti $(WEB_CONTAINER) bash

prepare:
	@echo "Creating directories and setting permissions for $(PROJECT)..."
	docker exec $(WEB_CONTAINER) mkdir -p /.composer
	docker exec $(WEB_CONTAINER) chown $(shell id -u):$(shell id -g) /.composer
	docker exec $(WEB_CONTAINER) mkdir -p /.yarn
	docker exec $(WEB_CONTAINER) chown $(shell id -u):$(shell id -g) /.yarn
	docker exec $(WEB_CONTAINER) mkdir -p /.cache
	docker exec $(WEB_CONTAINER) chown $(shell id -u):$(shell id -g) /.cache
	docker exec $(WEB_CONTAINER) rm -fr app/cache/*
	docker exec $(WEB_CONTAINER) rm -fr app/logs/*
	docker exec $(WEB_CONTAINER) rm -fr app/spool/*

install:
	@echo "Installing yarn dependencies for $(PROJECT)..."
	docker exec --user $(shell id -u):$(shell id -g) $(WEB_CONTAINER) yarn install --ignore-optional --force
	@echo "Installing composer depencencies for $(PROJECT)..."
	docker exec --user $(shell id -u):$(shell id -g) $(WEB_CONTAINER) composer install
