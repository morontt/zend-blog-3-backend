#!/bin/bash

# cache

rm -R ./app/cache/*

# vendors

composer self-update
composer install --optimize-autoloader --prefer-dist

# migrations

php app/console doctrine:migrations:migrate --env=prod --no-interaction

# assetic

php app/console assetic:dump --env=prod --no-debug

# cache

rm -R ./app/cache/*
