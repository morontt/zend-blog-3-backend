#!/usr/bin/env bash

FORCEFIX=0
while getopts ":f" opt; do
  case $opt in
    f)
      FORCEFIX=1
  esac
done

if [[ $FORCEFIX == 1 ]];
then
    bin/php-cs-fixer fix --config-file=var/php_cs.php
else
    bin/php-cs-fixer fix --dry-run --diff --config-file=var/php_cs.php
fi
