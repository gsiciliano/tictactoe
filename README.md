# Tic Tac Toe

## Description

A RESTful API for play a tic-tac-toe game. 

This respository is intended for testing only, use in production is discouraged.

Here's a list of endpoint

| Endpoint                 | Auth | Description                                                                          |
| ------------------------ | ---- | ------------------------------------------------------------------------------------ |
| **GET** api/v1/games   | yes  | Return a list of all games stored in database |
| **POST** api/v1/games    | yes  | used to create a new game, returns its UUID|
| **GET** api/v1/games/{uuid} | yes  | return a game|
| **GET** api/v1/games/{uuid}/turns | yes  | returns the list of all turns in a given game|
| **POST** api/v1/games/{uuid}/turns | yes | create a new turn for the player with a location|

## Requirements

In order to run this application an internet connection is required and you must install at least:

- [Git](https://git-scm.com/)
- [Docker engine](https://docs.docker.com/engine/install/)
- [Docker compose](https://docs.docker.com/compose/install/)
- [WSL](https://docs.microsoft.com/en-us/windows/wsl/setup/environment) (if you run under Windows)

## Installation, setup and first run on a local environment

First of all you must clone this repo: `git clone https://github.com/gsiciliano/tictactoe.git`

### Use following instructions to initialize your local environment

- `cd tictactoe`
- `docker-compose build`
- `docker-composeup -d`
  
### Run application's containers

use `docker-compose up -d` to start application's containers;

### Run test suite (only for local environment)

in local environment configuration you can run test suite using following command:

- for run tests with explictit descriptions:
  - `docker exec -it tic_tac_toe_app php artisan test`

### Production readiness

Note that this application runs on http protocol in local environment due to privacy problems with localhost ssl certificates on some browsers, to run in production you must provide ssl certificates and edit nginx container configuration.

To follow best-practice for production enviroments consider use of CI/CD tool instead of running commands manually.

All passwords stored in configuration files and seeder are intended for testing only, make sure to replace them in production environment for best data safety

## Test application on local environment

### with your browser

navigate to <http://localhost> for swagger docs

you can use following credentials for test api via swaggerdocs or postman collection

`client_id: 1`

`client_secret: 0Vw967ioyYp2zozSZS3cOaivSTycOJW0SNo9KfHP`

### with [Postman](https://www.postman.com/)

you can import this [postman collection](postman/tic-tac-toe-api.postman_collection.json) to try tic tac toe API.

## How it works

The Tic Tac Toe API is composed by following containers:

- Ngingx proxy web server container
- Php application container

Location are considered as follow according to tic-tac-toe definition in [wikipedia](https://en.wikipedia.org/wiki/Tic-tac-toe) :

|   |   |   |
|---|---|---|
| 1 | 2 | 3 |
| 4 | 5 | 6 |
| 7 | 8 | 9 |

When a new game or turn are posted data are stored in a sqlite database.

_**Note that no volumes for containers are created: if you stop and restart application nothing happens on database, but if you remove containers all data will be erased.**_

