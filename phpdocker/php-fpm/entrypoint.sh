#!/bin/bash

cd /application

echo -e "\nSelf-update Composer"
composer self-update

echo -e "\nInstall/update Composer dependencies"
composer install

echo -e "\nRun DB migrations on DEV environment"
php bin/console doctrine:migrations:migrate

echo -e "\nRun DB migrations on TEST environment"
php bin/console --env test doctrine:migrations:migrate

echo -e "\nLoad DB fixtures on DEV environment"
php bin/console doctrine:fixtures:load --no-interaction

echo -e "\nLoad DB fixtures on TEST environment"
php bin/console --env test doctrine:fixtures:load --no-interaction

/usr/sbin/php-fpm8.1
