FROM xelbot/docker-zendblog-admin

VOLUME ["/var/www/html", "/var/www/resources"]

ARG HOST_UID=1000

RUN yes | pecl install xdebug-3.1.6 \
    && docker-php-ext-enable xdebug \
    && cp ${PHP_INI_DIR}/php.ini-development ${PHP_INI_DIR}/php.ini \
    && sed -i 's/;date.timezone =/date.timezone = Europe\/Moscow/' ${PHP_INI_DIR}/php.ini \
    && sed -i 's/memory_limit = 128M/memory_limit = 512M/' ${PHP_INI_DIR}/php.ini \
    ;

RUN curl -L -o /tmp/cavif.deb https://github.com/kornelski/cavif-rs/releases/download/v1.5.3/cavif_1.5.3_amd64.deb \
    && dpkg -i /tmp/cavif.deb

RUN usermod -u ${HOST_UID} www-data && groupmod -g ${HOST_UID} www-data

EXPOSE 80
