#!/bin/bash

docker exec rhinoceros bash -c "php app/console assets:install"
docker compose run --rm nodejs bash -c "./buildapp_js.sh"
docker exec rhinoceros bash -c "chown -R www-data:www-data ."

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

replace_old_asset web/dist/mttblog_tmp_main.min.css    web/dist/mttblog_main.min.css
replace_old_asset web/dist/mttblog_tmp_preview.min.css web/dist/mttblog_preview.min.css
replace_old_asset web/dist/mttblog_tmp.min.js          web/dist/mttblog.min.js
