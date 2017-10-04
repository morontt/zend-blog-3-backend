#!/usr/bin/env bash

cd /tmp

git clone https://github.com/facebook/watchman.git
cd watchman
git checkout v4.7.0

./autogen.sh
./configure --without-python

make -j$(nproc)
make install

cd ..
rm -rf ./watchman
