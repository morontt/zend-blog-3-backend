FROM php:7.2-apache-stretch

MAINTAINER Alexander Harchenko <morontt@gmail.com>

VOLUME ["/var/www/html", "/var/www/resources"]

ARG HOST_UID=1000

ENV DEBIAN_FRONTEND noninteractive
ENV PHP_CPPFLAGS="$PHP_CPPFLAGS -std=c++11"
ENV TZ "Europe/Kiev"

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

COPY ./custom-apt.sh /tmp/custom-apt.sh
RUN bash /tmp/custom-apt.sh

RUN docker-php-ext-install -j$(nproc) intl zip pdo_mysql opcache \
    && pecl install imagick \
    && docker-php-ext-enable imagick

COPY ./.bashrc /root/.bashrc
RUN cp ${PHP_INI_DIR}/php.ini-production ${PHP_INI_DIR}/php.ini \
    && sed -i 's/;date.timezone =/date.timezone = Europe\/Kiev/' ${PHP_INI_DIR}/php.ini \
    && echo "LogFormat \"%a %l %u %t \\\"%r\\\" %>s %O \\\"%{User-Agent}i\\\"\" mainlog" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite remoteip && a2dismod deflate -f

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN set -x && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer global require hirak/prestissimo --prefer-dist --no-interaction \
    && curl -sS -o /root/.bash_aliases https://raw.githubusercontent.com/morontt/dotfiles/master/ubuntu/.bash_aliases

RUN usermod -u ${HOST_UID} www-data && groupmod -g ${HOST_UID} www-data

EXPOSE 80
