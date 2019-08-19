#!/bin/bash

WEB_CONTAINER=celsius3_web

# Clear directories
docker exec $WEB_CONTAINER rm -fr web/build/
docker exec $WEB_CONTAINER rm -fr web/bundles/
docker exec $WEB_CONTAINER rm -fr app/cache/*
docker exec $WEB_CONTAINER rm -fr app/logs/*
docker exec $WEB_CONTAINER rm -fr app/spool/*

# Install dependencies
docker exec $WEB_CONTAINER yarn install --ignore-optional --force
docker exec $WEB_CONTAINER composer install