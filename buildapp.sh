#!/usr/bin/env bash

cd ./spa

while getopts ":ri" opt; do
  case $opt in
    r)
      rm -rf node_modules
      rm -rf bower_components
      ;;
    i)
      bower install
      npm install
      ;;
  esac
done

ember build --output-path ./../web/spa
