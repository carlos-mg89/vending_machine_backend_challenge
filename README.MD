Vending machine - Backend challenge
===================================

## Requirements

Only `Composer` and `Docker Compose` are required.

## How to set up environment

Run `docker-compose up`

After that the following Docker containers will be up and running:

- PHP service
- MariaDB service (which will have a volume attached so state is persisted across different startups)
- Nginx service

## Access web app

You can visit it on `127.0.0.1`

## Access database

Review the docker-compose.yml to check for all DB info.

## Running tests

Run either
```
bin/phpunit
```
or
```
./vendor/bin/simple-phpunit
```
