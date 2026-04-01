#!/usr/bin/env bash

# Run inside PHP container

composer install --optimize-autoloader --prefer-dist

php bin/console doctrine:migrations:migrate --env=prod --no-interaction

php bin/console assets:install web --env=prod
php bin/console fos:js-routing:dump --env=prod --pretty-print --target=web/dist/fos_js_routes.js

rm -R ./var/cache/*
php bin/console cache:warmup --env=prod

chown -R www-data:www-data .
