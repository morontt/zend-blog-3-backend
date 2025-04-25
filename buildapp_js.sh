#!/usr/bin/env bash

# Run inside nodejs container

WITH_INSTALL=""

while getopts ":ri" opt; do
  case $opt in
    r)
      rm -rf node_modules
      rm -rf bower_components
      rm -rf spa/node_modules
      rm -rf spa/bower_components
      ;;
    i)
      WITH_INSTALL="yes"
      ;;
    *)
      echo "unknown flag :("
      exit 1
  esac
done

if [[ "$WITH_INSTALL" == "yes" ]]; then
  bower install
  # cp -R bower_components/bootstrap/fonts web
  cp -R bower_components/jquery-ui/themes/base/images web/dist
  yarn install
fi

cd ./spa || exit

if [[ "$WITH_INSTALL" == "yes" ]]; then
  bower install
  yarn install
fi

ember build --output-path ./../web/spa

cd ..

grunt
