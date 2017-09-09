FROM nginx:1.10.3

MAINTAINER Alexander Harchenko <morontt@gmail.com>

COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./server.key /etc/nginx/ssl/privkey.pem
COPY ./cert.pem /etc/nginx/ssl/cert.pem

EXPOSE 443
