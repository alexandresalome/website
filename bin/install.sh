#!/bin/bash
DIR=`php -r "echo dirname(dirname(realpath('$0')));"`
cd "$DIR"

rm -Rf app/cache/* app/logs/*

if [ ! -f "app/config/parameters.ini" ]; then
  cp app/config/parameters.ini-dist app/config/parameters.ini
fi

./app/console doctrine:database:drop --force
./app/console doctrine:database:create
./app/console doctrine:schema:update --force
./app/console doctrine:fixtures:load

