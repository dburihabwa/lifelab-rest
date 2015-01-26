#! /bin/bash
#####################################################
#                                                   #
# Requires the presence of a lifelab mysql database #
#                                                   #
#####################################################
curl -sS https://getcomposer.org/installer | php
rm -rf vendor/ bin
php composer.phar install
rm -rf app/cache
cp app/config/parameters.yml.dist app/config/parameters.yml
sed  -i 's/<database username>/symfony/' app/config/parameters.yml
sed  -i 's/<database password>/symfony/' app/config/parameters.yml
php app/console doctrine:schema:update --force
bin/phpunit -c app/
