#!/bin/bash

if [[ ! -f ./dump.sql ]]; then
    echo -e "file dump.sql not exist"
    exit 1
fi

mysql -u morontt -p"$1" <<EOF
DROP DATABASE morontt_db;
CREATE DATABASE morontt_db;
USE morontt_db;
\. dump.sql
\. app/DoctrineMigrations/sql/drop_migrations.sql
EOF

docker exec rhinoceros bash -c "php app/console do:mi:mi && php app/console mtt:posts:update"
docker exec rhinoceros bash -c "chown -R www-data:www-data ."
