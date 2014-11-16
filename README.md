## Requirements

* php5
* php driver for database of choice

## Setup the server

### Install composer
```bash
curl -sS https://getcomposer.org/installer | php
```

### Install dependencies

```bash
php composer.phar update
```

## Start the server

```bash
php app/console server:run
```

## Run the tests
To launch the tests, run the follwing command:

```bash
bin/phpunit -c app/
```
