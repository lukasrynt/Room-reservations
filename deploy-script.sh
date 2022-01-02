#!/usr/bin/env bash

docker-compose up -d
composer install --no-interaction
php ./bin/console --no-interaction doctrine:migrations:migrate
cd ./seeds
bash run_seeds.sh
cd ..
export PATH="$HOME/.symfony/bin:$PATH"
symfony server:start -d