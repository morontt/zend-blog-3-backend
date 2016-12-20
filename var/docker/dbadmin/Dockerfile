FROM phpmyadmin/phpmyadmin

MAINTAINER Alexander Harchenko <morontt@gmail.com>

RUN sed -i "s/session.gc_maxlifetime = 1440/session.gc_maxlifetime = 28800/" /etc/php5/php.ini
