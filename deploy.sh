#!/bin/bash

# cache ans static files

rm -R ./var/cache/*
rm -R ./web/bundles/*
rm -R ./web/css/*
rm -R ./web/js/*
rm -R ./web/spa/*
rm -R ./web/assetic/*

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
php app/console cache:warmup --env=prod

chown -R www-data:www-data var/cache var/logs web
