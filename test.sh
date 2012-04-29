#!/bin/bash
php app/console doctrine:database:drop --force --env=test
php app/console doctrine:database:create --env=test
php app/console doctrine:schema:create --env=test
php app/console doctrine:fixtures:load --env=test

phpunit -c app
exit $?

