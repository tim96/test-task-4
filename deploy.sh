# install vendors
composer install
composer dump-autoload --no-dev --classmap-authoritative

# apply migration
./vendor/bin/doctrine-migrations migrate --no-interaction
