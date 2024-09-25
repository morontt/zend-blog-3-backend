FROM xelbot/docker-zendblog-admin

VOLUME ["/var/www/html", "/var/www/resources"]

ARG HOST_UID=1000

RUN sed -i 's/php app\/console/php bin\/console/' /root/.bashrc
RUN usermod -u ${HOST_UID} www-data && groupmod -g ${HOST_UID} www-data

EXPOSE 80
