FROM nginx:1.14

MAINTAINER Alexander Harchenko <morontt@gmail.com>

VOLUME ["/var/www/resources"]

COPY ./conf/web.conf /etc/nginx/conf.d
COPY ./conf/cdn.conf /etc/nginx/conf.d
COPY ./conf/old.conf /etc/nginx/conf.d
COPY ./conf/ghost.conf /etc/nginx/conf.d
COPY ./conf/new_front.conf /etc/nginx/conf.d

EXPOSE 443
