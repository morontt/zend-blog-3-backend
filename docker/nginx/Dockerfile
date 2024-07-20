FROM nginx:1.18

MAINTAINER Alexander Harchenko <morontt@gmail.com>

VOLUME ["/var/www/html", "/var/www/old_resources", "/var/www/app"]

RUN sed -i "s/# export LS_OPTIONS='--color=auto'/export LS_OPTIONS='--color=auto -ah'/" /root/.bashrc \
    && sed -i "s/# alias ls/alias ls/" /root/.bashrc \
    && sed -i "s/# alias ll/alias ll/" /root/.bashrc \
  ;

RUN sed -i "s/}//" /etc/nginx/mime.types \
    && echo "    font/otf      otf;" >> /etc/nginx/mime.types \
    && echo "    font/ttf      ttf;" >> /etc/nginx/mime.types \
    && echo "    image/avif    avif;" >> /etc/nginx/mime.types \
    && echo "}" >> /etc/nginx/mime.types

COPY ./conf/* /etc/nginx/conf.d
COPY ./nginx.conf /etc/nginx/nginx.conf

EXPOSE 443
