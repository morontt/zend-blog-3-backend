FROM nginx:1.14

MAINTAINER Alexander Harchenko <morontt@gmail.com>

VOLUME ["/var/www/resources"]

COPY ./web.conf /etc/nginx/conf.d
COPY ./fullchain.pem /etc/nginx/ssl/cert.pem
COPY ./privkey.pem /etc/nginx/ssl/privkey.pem

EXPOSE 443
