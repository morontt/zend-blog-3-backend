FROM node:8-stretch

COPY ./watchman-install.sh /tmp/watchman-install.sh
RUN bash /tmp/watchman-install.sh

#RUN npm install npm@latest -g \
RUN npm install -g bower uglifycss uglify-js ember-cli@2.4.2 grunt-cli

COPY ./.bashrc /root/.bashrc

WORKDIR /var/www/html
