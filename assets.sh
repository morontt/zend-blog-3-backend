#!/bin/bash

docker compose run --rm rhinoceros bash -c "php app/console assets:install"
docker compose run --rm nodejs bash -c "./buildapp_js.sh"
docker compose run --rm rhinoceros bash -c "chown -R www-data:www-data ."
