Backend Zend-blog-3
===================

Work in progress

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
