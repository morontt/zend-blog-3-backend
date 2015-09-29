#!/usr/bin/env bash

cd ./spa

while getopts ":i" opt; do
  case $opt in
    i)
      rm -rf node_modules
      npm install
      rm -rf bower_components
      bower install
      ;;
  esac
done

ember build --output-path ./../web/spa
