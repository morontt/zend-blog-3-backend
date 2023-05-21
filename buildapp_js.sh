#!/usr/bin/env bash

# Run inside nodejs container

bower install --allow-root
cp -R bower_components/bootstrap/fonts web
cp -R bower_components/jquery-ui/themes/base/images web/dist
yarn install

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
