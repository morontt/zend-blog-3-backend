#!/usr/bin/env bash

curl -sL https://deb.nodesource.com/setup_4.x | bash -

apt-key adv --keyserver pgp.mit.edu --recv-keys 5072E1F5
echo "deb http://repo.mysql.com/apt/debian/ jessie mysql-5.7" > /etc/apt/sources.list.d/mysql.list

apt-get update
apt-get install -y --no-install-recommends nodejs mysql-client
rm -rf /var/lib/apt/lists/*
