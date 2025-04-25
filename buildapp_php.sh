#!/usr/bin/env bash

# Run inside PHP container

composer install --optimize-autoloader --prefer-dist

php bin/console doctrine:migrations:migrate --env=prod --no-interaction

php bin/console assets:install web --env=prod

rm -R ./var/cache/*
php bin/console cache:warmup --env=prod

chown -R www-data:www-data .
