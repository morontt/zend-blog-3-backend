#!/usr/bin/env bash

curl -sL https://deb.nodesource.com/setup_6.x | bash -

apt-key adv --keyserver pgp.mit.edu --recv-keys 5072E1F5
echo "deb http://repo.mysql.com/apt/debian/ jessie mysql-5.7" > /etc/apt/sources.list.d/mysql.list

apt-get update
apt-get install -y apt-utils
apt-get install -y --no-install-recommends nodejs mysql-client zlib1g-dev libicu-dev git nano zip pngquant

apt-get clean

rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

curl -sS -o /tmp/icu.tar.gz -L http://download.icu-project.org/files/icu4c/59.1/icu4c-59_1-src.tgz \
    && tar -zxf /tmp/icu.tar.gz -C /tmp && cd /tmp/icu/source && ./configure --prefix=/usr/local \
    && make -j$(nproc) && make install \
    && rm -rf /tmp/icu
