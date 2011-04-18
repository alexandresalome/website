#!/bin/bash
rm -Rf app/cache/* app/logs/*
./app/console doctrine:database:drop --force --env=test
./app/console doctrine:database:create --env=test
./app/console doctrine:schema:update --force --env=test
./app/console doctrine:data:load --env=test
phpunit -c app/
