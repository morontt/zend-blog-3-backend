#!/bin/bash

rm -R ./var/cache/*
rm -R ./web/bundles/*
rm -R ./web/spa/*
rm -R ./web/dist/*

docker exec rhinoceros bash -c "./buildapp_php.sh"
docker compose run --rm nodejs bash -c "./buildapp_js.sh -i"
docker exec rhinoceros bash -c "chown -R www-data:www-data ."
