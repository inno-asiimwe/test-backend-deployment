# watch-tower-server
[![CircleCI](https://circleci.com/gh/andela/watch-tower-server.svg?style=svg&circle-token=c58f957124c43fb76e8dab2c1ea9c117fca9b05d)](https://circleci.com/gh/andela/watch-tower-server)
[![Maintainability](https://api.codeclimate.com/v1/badges/85a8802af6cfdd13880b/maintainability)](https://codeclimate.com/repos/5bb4b15539130702b6008fb4/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/85a8802af6cfdd13880b/test_coverage)](https://codeclimate.com/repos/5bb4b15539130702b6008fb4/test_coverage)

## Description
WatchTower Server is the backend for the WatchTower application which serves the purpose automate tracking and management of Fellow performance ratings within the D0 space at Andela.

## Table of Contents
- [Key Features](#key-features)
- [Setup](#setup)
    - [Dependencies](#dependencies)
    - [Getting Started](#getting-started)
- [Running the application](#running-the-application)
- [Testing](#testing)
- [Development Guidelines](#development-guidelines)


### Key Features
    1. Customized Performance Reporting 
    2. Automated Email Notifications

## Setup

### Dependencies

* [PHP 7](http://php.net/) - popular general-purpose scripting language suited to web development
* [Laravel 5.7](https://laravel.com/docs/5.7) - A web application framework built with PHP
* [Redis](https://redis.io/) - an in-memory data structure store

### Getting Started

Setting up project in development mode

* Ensure PHP 7.0 is installed by running:
```
php -v
```

* Ensure that the redis server is installed by running:
```
redis-server -v
```
* If the redis server is not installed, enter the command below to do so. 
    * Linux
    ```
    sudo apt-get install redis-server
    ```
    * macOS
    ```
    brew install redis
    ```

* Ensure the redis server is working: 

    * Start the server
        * Linux
        ```
        sudo systemctl enable redis-server.service
        ```
        * macOS
        ```
        brew services start redis
        ```
    * Ping the server to test
    ```
    redis-cli ping
    ```
  A successful test should return `PONG` in the console

* Clone the repository to your machine and navigate into it:
```
git clone https://github.com/andela/watch-tower-server.git && cd watch-tower-server
```
* Install application dependencies:
```
composer install
```
* Create a *.env* file and include the necessary environment variables to configure mail and redis, prefixed with `MAIL_` and `REDIS_` respectively 


## Running the application
Inside the project root folder, run the command below in your console
```
php artisan serve
```

## Testing

Run the command below:

```
composer test
```

## Development Guidelines
* Follow branch naming, commit message and pull request conventions [here](https://github.com/andela/engineering-playbook/tree/master/5.%20Developing/Conventions)
* Follow PSR-2: Coding Style Guide for PHP [here](https://www.php-fig.org/psr/psr-2/)
* Make use of this [guide](https://github.com/andela/engineering-playbook/tree/master/5.%20Developing/API%20Design) when designing API endpoints
