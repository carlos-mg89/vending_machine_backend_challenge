#!/bin/bash

cd /application

echo -e "\nSelf-update Composer"
composer self-update

echo -e "\nInstall/update Composer dependencies"
composer install

/usr/sbin/php-fpm8.1
