#!/bin/bash

# cache

rm -R ./var/cache/*

# vendors

composer self-update
composer install --optimize-autoloader --prefer-dist

bower install --allow-root
./buildapp.sh -i

# migrations

php app/console doctrine:migrations:migrate --env=prod --no-interaction

# assetic

php app/console assetic:dump --env=prod --no-debug

# cache

rm -R ./var/cache/*
