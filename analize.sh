#!/bin/bash

docker run --rm -v $(pwd):/app phpstan/phpstan analyse /app/src
