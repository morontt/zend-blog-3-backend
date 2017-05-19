FROM php:7.0-apache

MAINTAINER Alexander Harchenko <morontt@gmail.com>

VOLUME ["/var/www/html"]

ARG HOST_UID=1000

ENV DEBIAN_FRONTEND noninteractive

RUN echo Europe/Kiev > /etc/timezone && \
    dpkg-reconfigure -f noninteractive tzdata

RUN curl -sL https://deb.nodesource.com/setup_4.x | bash -

RUN apt-get update && apt-get install -y apt-utils
RUN apt-get install -y \
    zlib1g-dev libicu-dev \
    git nano zip mysql-client \
    nodejs

RUN docker-php-ext-install intl zip pdo_mysql opcache

RUN npm install -g bower uglifycss uglify-js ember-cli@2.4.2

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" \
    > /usr/local/etc/php/conf.d/xdebug.ini

COPY ./.bashrc /root/.bashrc
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
COPY ./php.ini /usr/local/etc/php/
RUN echo "LogFormat \"%a %l %u %t \\\"%r\\\" %>s %O \\\"%{User-Agent}i\\\"\" mainlog" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite remoteip

RUN curl -sS -o /root/.bash_aliases https://raw.githubusercontent.com/morontt/dotfiles/master/ubuntu/.bash_aliases

RUN set -x && curl -sSL -o composer.phar https://github.com/composer/composer/releases/download/1.3.3/composer.phar \
    && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer

RUN usermod -u ${HOST_UID} www-data
RUN groupmod -g ${HOST_UID} www-data

EXPOSE 80
