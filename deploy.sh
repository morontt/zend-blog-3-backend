#!/bin/bash

# cache

sudo rm -R ./app/cache/*

# vendors

sudo composer self-update
composer install --optimize-autoloader --prefer-dist

npm install
bower install

# migrations

php app/console doctrine:migrations:migrate --env=prod --no-interaction

# assetic

grunt
php app/console assetic:dump --env=prod --no-debug

# cache

sudo rm -R ./app/cache/*
