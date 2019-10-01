#!/usr/bin/env bash

apt-get update && apt-get install -y apt-utils gnupg2

apt-get update && apt-get install -y --no-install-recommends \
    zlib1g-dev libicu-dev git nano zip unzip pngquant nodejs npm default-mysql-client libmagickwand-dev

apt-get clean

rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
