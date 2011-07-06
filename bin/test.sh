#!/bin/bash
rm -Rf app/cache/* app/logs/*

if [ ! -f "app/config/parameters.ini" ]; then
  cp app/config/parameters.ini-dist app/config/parameters.ini
fi

./app/console doctrine:database:drop --force --env=test
./app/console doctrine:database:create --env=test
./app/console doctrine:schema:update --force --env=test
./app/console doctrine:fixtures:load --env=test

#phpunit -c app/
exit $?
