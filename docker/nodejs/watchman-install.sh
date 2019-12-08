#!/usr/bin/env bash

apt-get update && apt-get install -y --no-install-recommends \
    autoconf automake libtool \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

cd /tmp

git clone https://github.com/facebook/watchman.git
cd watchman
git checkout v4.9.0

./autogen.sh
./configure --without-python

make -j$(nproc)
make install

cd ..
rm -rf ./watchman
