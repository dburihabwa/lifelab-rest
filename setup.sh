#! /bin/sh

# Drop the database
php app/console doctrine:database:drop
# Create the database
php app/console doctrine:database:create
# Generate getters/setters for your entities
php app/console doctrine:generate:entities LifeLabRestBundle 
# Update the database schema
php app/console doctrine:schema:update --force 
# List all available routes
php app/console route:debug
