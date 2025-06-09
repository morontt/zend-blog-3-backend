FROM xelbot/docker-zendblog-admin:1.0.2

VOLUME ["/var/www/html", "/var/www/resources"]

ARG HOST_UID=1000

RUN yes | pecl install xdebug-3.4.2 \
    && docker-php-ext-enable xdebug \
    && cp ${PHP_INI_DIR}/php.ini-development ${PHP_INI_DIR}/php.ini \
    && sed -i 's/;date.timezone =/date.timezone = Europe\/Moscow/' ${PHP_INI_DIR}/php.ini \
    && sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 7M/' ${PHP_INI_DIR}/php.ini \
    && sed -i 's/memory_limit = 128M/memory_limit = 512M/' ${PHP_INI_DIR}/php.ini \
    ;

RUN usermod -u ${HOST_UID} www-data && groupmod -g ${HOST_UID} www-data

EXPOSE 80
