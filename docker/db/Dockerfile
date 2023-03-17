FROM mysql:5.7

MAINTAINER Alexander Harchenko <morontt@gmail.com>

VOLUME ["/var/lib/mysql"]

ARG HOST_UID=1000
ENV TZ "Europe/Moscow"

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && echo "default_time_zone = 'Europe/Moscow'" >> /etc/mysql/mysql.conf.d/mysqld.cnf \
    && echo "general_log = 'ON'" >> /etc/mysql/mysql.conf.d/mysqld.cnf \
    && echo "general_log_file = /var/log/mysql-general.log" >> /etc/mysql/mysql.conf.d/mysqld.cnf

RUN usermod -u ${HOST_UID} mysql \
    && groupmod -g ${HOST_UID} mysql

EXPOSE 3306
