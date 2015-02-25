# lifelab-rest
[![Build Status](https://ks.gnap.be/jenkins/buildStatus/icon?job=lifelab-rest)](https://ks.gnap.be/jenkins/job/lifelab-rest/)


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

## Install the angular app dependencies

*if you haven't bower, install it :*
```bash
sudo apt-get install npm
sudo npm install -g bower
```

*Install the angular app dependencies*
```bash
cd web/
bower install
```

*ATTENTION: * In order to be able to download the dependencies over an overly-firewalled network, you should run the following command (at least the first time)

```bash
git config url."https://".insteadOf git://
```

## Run the tests
To launch the tests, run the follwing command:

```bash
bin/phpunit -c app/
```
