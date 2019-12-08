#!/usr/bin/env bash

# Run inside nodejs container

bower install --allow-root

cd ./spa

while getopts ":ri" opt; do
  case $opt in
    r)
      rm -rf node_modules
      rm -rf bower_components
      ;;
    i)
      bower install --allow-root
      yarn install
      ;;
  esac
done

ember build --output-path ./../web/spa

cd ..
grunt
