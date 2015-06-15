Backend Zend-blog-3
===================

Work in progress

[![CircleCI Shield](https://circleci.com/gh/morontt/zend-blog-3-backend.svg?style=shield&circle-token=5e88cc76a02111e39b022a28d12cea94a688127f)](https://circleci.com/gh/morontt/zend-blog-3-backend)
[![Stories in Ready](https://badge.waffle.io/morontt/zend-blog-3-backend.svg?label=ready&title=Ready)](http://waffle.io/morontt/zend-blog-3-backend)
[![Stack Share](http://img.shields.io/badge/tech-stack-0690fa.svg?style=flat)](http://stackshare.io/morontt/zend-blog-3-backend)

### Setup project

#### Requirements

- php 5.4+
- nodejs
- npm

#### Install packages for Node.js

    sudo npm install -g grunt-cli
    sudo npm install -g bower
    sudo npm install -g uglifycss
    sudo npm install -g uglify-js

If npm is not installed (Debian/Ubuntu)

    sudo apt-get install nodejs

#### Install vendors

    composer install
    npm install

#### Compiling Ember.js template

    grunt

#### Install assets

    app/console assetic:dump -e prod --no-debug
