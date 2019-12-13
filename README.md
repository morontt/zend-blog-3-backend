Backend Zend-blog-3
===================

Work in progress

[![CircleCI Shield](https://circleci.com/gh/morontt/zend-blog-3-backend.svg?style=shield&circle-token=5e88cc76a02111e39b022a28d12cea94a688127f)](https://circleci.com/gh/morontt/zend-blog-3-backend)
[![Stack Share](http://img.shields.io/badge/tech-stack-0690fa.svg?style=flat)](http://stackshare.io/morontt/zend-blog-3-backend)

### Setup project

```sh
cp .env{.dist,}
cp app/config/parameters.yml{.dist,}
```

#### Requirements

- docker
- docker-compose

#### Install

```sh
docker-compose up --build
```

#### Install vendors, build app, etc.

```sh
docker exec -it container_name bash
./deploy.sh
```
