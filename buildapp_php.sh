#!/usr/bin/env bash

# Run inside PHP container

composer install --optimize-autoloader --prefer-dist

php app/console doctrine:migrations:migrate --env=prod --no-interaction

php app/console assets:install --env=prod

rm -R ./var/cache/*
php app/console cache:warmup --env=prod

chown -R www-data:www-data .
