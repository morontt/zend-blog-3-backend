FROM php:7.1.8-apache

MAINTAINER Alexander Harchenko <morontt@gmail.com>

VOLUME ["/var/www/html"]

ARG HOST_UID=1000

ENV DEBIAN_FRONTEND noninteractive

RUN echo Europe/Kiev > /etc/timezone && \
    dpkg-reconfigure -f noninteractive tzdata

COPY ./custom-apt.sh /tmp/custom-apt.sh
RUN bash /tmp/custom-apt.sh

RUN apt-get update && apt-get install -y apt-utils && apt-get install -y \
    zlib1g-dev libicu-dev git nano zip mysql-client \
    nodejs

RUN docker-php-ext-install intl zip pdo_mysql opcache

RUN npm install -g bower uglifycss uglify-js ember-cli@2.4.2

COPY ./.bashrc /root/.bashrc
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
COPY ./php.ini /usr/local/etc/php/
RUN echo "LogFormat \"%a %l %u %t \\\"%r\\\" %>s %O \\\"%{User-Agent}i\\\"\" mainlog" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite remoteip && a2dismod deflate -f

RUN set -x && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && curl -sS -o /root/.bash_aliases https://raw.githubusercontent.com/morontt/dotfiles/master/ubuntu/.bash_aliases

RUN usermod -u ${HOST_UID} www-data && groupmod -g ${HOST_UID} www-data

EXPOSE 80
