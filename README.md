# demo-doctrine-behaviors

Demo of the [KnpLabs/DoctrineBehaviors](https://github.com/KnpLabs/DoctrineBehaviors) component

## Installation
composer install
docker-compose up -d (creates the database)
symfony console doctrine:database:create
symfony console doctrine:migration:migrate
symfony console doctrine:fixtures:load
