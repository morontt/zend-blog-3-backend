#!/bin/bash

rm -R ./var/cache/*
rm -R ./web/bundles/*
rm -R ./web/spa/*

docker exec rhinoceros bash -c "./buildapp_php.sh"
docker compose run --rm nodejs bash -c "./buildapp_js.sh -i"

function replace_old_asset() {
  NEW_FILE=$1
  TARGET=$2

  MD5_NEW=$(md5sum $NEW_FILE | awk '{print $1}')
  MD5_OLD=$(md5sum $TARGET | awk '{print $1}')

  echo -e "\nmd5: $MD5_NEW  \033[33m$NEW_FILE\033[0m"
  echo -e "md5: $MD5_OLD  \033[33m$TARGET\033[0m"

  if [[ "$MD5_NEW" != "$MD5_OLD" ]]; then
    cat $NEW_FILE > $TARGET
    echo -e "Replace \033[33m$TARGET\033[0m"
  fi
}

platform="$(uname -s)"
current_user="$(whoami)"
case "$platform" in
  Linux)
    sudo chown -R $current_user:$current_user .
    ;;
esac

replace_old_asset web/dist/mttblog_tmp_main.min.css    web/dist/mttblog_main.min.css
replace_old_asset web/dist/mttblog_tmp_preview.min.css web/dist/mttblog_preview.min.css
replace_old_asset web/dist/mttblog_tmp.min.js          web/dist/mttblog.min.js
