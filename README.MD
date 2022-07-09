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

## Initial data

There are some Doctrine fixtures that are inserted into the database every time you start the Docker services.

Basically, we are adding the 0.05, 0.10, 0.25, 1 coins, and the Water, Juice and Soda purchasable items.

They all come with some initial stock, but that can be changed by using the API endpoints.

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

## How to use the API

Some API endpoints throw exceptions depending on certain invalid situations. Like requesting for an unknown item.

### Insert money endpoint

Example to add a 0.25 coin: `http://127.0.0.1/coin/0.25`
Example to add a 1.00 coin: `http://127.0.0.1/coin/1`

### Return inserted coins endpoint

Example to get all inserted coins back: `http://127.0.0.1/coin/RETURN-COIN`

### Purchase item endpoint

Example to purchase Water (you need to have inserted coins previously): `http://127.0.0.1/purchasable_item/GET-Water`

### Service endpoint

Base URL: `127.0.0.1/SERVICE`

URL parameters (both are optional, so you can set only the available coins, or the stock of items separately):

* items_stock => Use to define the stock of each item. It's a JSON array of objects with the "selector" property and the "stock" property
* available_coins => Use to set the coins that are available. It's just a JSON array of float values, each float value represents a coin. Can have repeated values. Previous available coins will be set to zero (the vending machine service person extracts the difference).

Example to set Soda item to have a stock of 2, and Water item a stock of 5:

```
http://127.0.0.1/SERVICE?items-stock=[{%22selector%22:%22Water%22,%22stock%22:5},{%22selector%22:%22Soda%22,%22stock%22:0}]
```

Example to set a 0.1 coin and a 0.25 coin:

```
http://127.0.0.1/SERVICE?available_coins=[0.1,0.25]
```
