#!/bin/bash

rm -R ./var/cache/*
rm -R ./web/bundles/*
rm -R ./web/spa/*
rm -R ./web/dist/*

docker-compose run rhinoceros bash -c "./buildapp_php.sh"
docker-compose run nodejs bash -c "./buildapp_js.sh -i"
docker-compose run rhinoceros bash -c "chown -R www-data:www-data ."
docker-compose down
