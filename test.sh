#!/bin/bash

docker exec hedgehog_mysql /bin/bash -c "echo \"
    DROP DATABASE IF EXISTS mttblog_test;
    CREATE DATABASE mttblog_test;
    GRANT ALL PRIVILEGES ON mttblog_test. * TO 'mttblog'
\" | mysql -uroot -pdocker"

docker-compose exec rhinoceros bash -c "app/console doctrine:migrations:migrate -e test --no-interaction \\
    && app/console doctrine:fixtures:load -e test --no-interaction"
docker-compose exec rhinoceros bash -c "bin/php-cs-fixer fix --dry-run --diff \\
    && chown -R www-data:www-data var \\
    && bin/behat @MttTestBundle \\
    && bin/phpspec run"
