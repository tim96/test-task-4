# running phpstan
./vendor/bin/phpstan analyse -l 7 -c phpstan.neon config src public

# running phpcs
./vendor/bin/phpcs --standard=PSR2 --ignore=src/Migrations/* config src

# running phpunit
./vendor/bin/phpunit

# running doctrine commands
./vendor/bin/doctrine
./vendor/bin/doctrine-dbal
./vendor/bin/doctrine-migrations
