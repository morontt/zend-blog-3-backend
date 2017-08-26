FROM phpmyadmin/phpmyadmin:4.7.3-1

MAINTAINER Alexander Harchenko <morontt@gmail.com>

RUN sed -i "s/session.gc_maxlifetime = 1440/session.gc_maxlifetime = 28800/" /etc/php7/php.ini
