#! /bin/bash
#####################################################
#                                                   #
# Requires the presence of a lifelab mysql database #
#                                                   #
#####################################################
# Clear caches
rm -rf vendor/
rm -rf bin/
rm -rf app/cache
# Update and install dependencies
php composer.phar update --no-interaction
php composer.phar install --no-interaction
# Set up config files
cp app/config/parameters.yml.dist app/config/parameters.yml
sed  -i 's/<database username>/symfony/' app/config/parameters.yml
sed  -i 's/<database password>/symfony/' app/config/parameters.yml
curl -sS https://getcomposer.org/installer | php
# Clean up database
php app/console doctrine:schema:drop --force
php app/console doctrine:schema:update --force
# Run tests
bin/phpunit -c app/ --log-junit log-junit.xml