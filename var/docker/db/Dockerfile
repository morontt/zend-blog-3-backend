FROM mysql:5.5

MAINTAINER Alexander Harchenko <morontt@gmail.com>

VOLUME ["/var/lib/mysql"]

ARG HOST_UID=1000

RUN usermod -u ${HOST_UID} mysql
RUN groupmod -g ${HOST_UID} mysql

EXPOSE 3306
