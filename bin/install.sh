#!/bin/bash
rm -Rf app/cache/* app/logs/*
./app/console doctrine:database:drop --force
./app/console doctrine:database:create
./app/console doctrine:schema:update --force
./app/console doctrine:data:load

