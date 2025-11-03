#!/usr/bin/env bash

rm ./docker/nginx/fullchain.pem ./docker/nginx/privkey.pem

scp witty:/etc/letsencrypt/live/xelbot.com/privkey.pem ./docker/nginx
scp witty:/etc/letsencrypt/live/xelbot.com/fullchain.pem ./docker/nginx
