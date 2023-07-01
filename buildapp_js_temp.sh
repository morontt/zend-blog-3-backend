#!/usr/bin/env bash

cd ./spa3 || exit

ember build --output-path ./../web/spa3

cd ..
