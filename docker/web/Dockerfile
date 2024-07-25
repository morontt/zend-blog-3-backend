FROM xelbot/docker-zendblog-admin

VOLUME ["/var/www/html", "/var/www/resources"]

RUN yes | pecl install xdebug-3.1.6 \
    && docker-php-ext-enable xdebug \
    && cp ${PHP_INI_DIR}/php.ini-development ${PHP_INI_DIR}/php.ini \
    && sed -i 's/;date.timezone =/date.timezone = Europe\/Moscow/' ${PHP_INI_DIR}/php.ini \
    && sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 7M/' ${PHP_INI_DIR}/php.ini \
    && sed -i 's/memory_limit = 128M/memory_limit = 512M/' ${PHP_INI_DIR}/php.ini \
    ;

EXPOSE 80
