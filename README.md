## On veut un mini serveur

```php bin/console server:run```

## On veut une page d'accueil

```php bin/console make:controller```

## On veut une base de données

> edit .env

```php bin/console doctrine:database:create```

```php bin/console make:entity```

https://symfony.com/doc/current/doctrine.html

https://symfony.com/doc/master/bundles/DoctrineMigrationsBundle/index.html

https://symfony.com/doc/current/doctrine/associations.html

## On veut des données de tests

https://symfony.com/doc/master/bundles/DoctrineFixturesBundle/index.html

```composer require --dev doctrine/doctrine-fixtures-bundle```

> creer DataFxitures/AppFixtures.php

```php bin/console doctrine:fixtures:load```

## On veut avoir des fixtures aléatoires

```composer require fzaninotto/faker```

> edit DataFxitures/AppFixtures.php

```php bin/console doctrine:fixtures:load```

```php bin/console doctrine:query:sql 'SELECT * FROM recette'```

## On veut automatiser la mise à jour de la date de création et la création d'un slug

https://symfony.com/doc/current/doctrine/common_extensions.html

```composer require stof/doctrine-extensions-bundle```

> edit packages>stof_doctrine_extensions.yml

> edit Entity/Recette.php Add       * @Gedmo\Timestampable(on="create") and slug props


> comment DataFxitures/AppFixtures.php setDateCreation

```php bin/console doctrine:query:sql 'SELECT * FROM recette'```

## On veut afficher les recettes et navigier
> edit controller pour ajouter le findAll et le show

https://symfony.com/doc/current/doctrine.html#fetching-objects-from-the-database

> edit templates/base pour rajouter bootstrap
> edit templates/default/index pour afficher chaque recette 

https://twig.symfony.com/doc/2.x/tags/for.html
> add {{ path('recette_show', {'slug': recette.slug}) }}
https://symfony.com/doc/current/templating.html#linking-to-pages

## passage en api rest
> composer require api

https://api-platform.com/docs/core/getting-started/

rajouter du mapping sur les entity
> * @ApiResource

curl -X GET "http://127.0.0.1:8000/api/recettes" -H "accept: application/ld+json"


## ajouter des tests

>  composer require --dev symfony/phpunit-bridge
> ./bin/phpunit


https://symfony.com/doc/current/testing.html

>  composer require --dev symfony/browser-kit symfony/css-selector
