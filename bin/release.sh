#!/bin/bash
baseDir=`php -r "echo dirname(dirname(realpath('$0')));"`
tempDir="`mktemp -d`"
version="`cat VERSION`"

echo "Application folder : $baseDir"
echo "Temporary folder   : $tempDir"
echo "Version            : $version"

# Copy project
cp -R * .git "$tempDir"

# Clean
cd $tempDir
git clean -fdx --exclude=vendor --exclude=app/config/parameters.ini
mkdir app/cache app/logs web/uploads web/media

# Update project
cd $tempDir
./bin/vendors install
php bin/build_bootstrap.php

# Generate CSS/JS
./app/console assetic:dump web/ --env=prod --no-debug


# Remove non-needed files
rm -Rf app/cache/* app/logs/*
find bin/    -type f -name "*" ! -name "rst2html-pygments" -delete
find vendor/ -type d -name ".git" | xargs rm -Rf
rm -Rf app/config/parameters.ini
rm -Rf web/bundles
rm -Rf web/config.php
rm -Rf web/app_dev.php
rm LICENCE deps*

# Compress
cd $tempDir
tar -czf "$baseDir/$version.tgz" *
rm -Rf $tempDir
