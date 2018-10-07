## On veut un mini serveur php

`php bin/console server:run`

## mais la prod tourne avec apache

`composer require symfony/apache-pack`
ça ajoute un htaccess dans le dossier public
https://symfony.com/doc/current/setup/web_server_configuration.html

## On veut une page d'accueil

`php bin/console make:controller`

## On veut une base de données

> edit .env

`php bin/console doctrine:database:create`

`php bin/console make:entity`

https://symfony.com/doc/current/doctrine.html

https://symfony.com/doc/master/bundles/DoctrineMigrationsBundle/index.html

https://symfony.com/doc/current/doctrine/associations.html

## On veut des données de tests

https://symfony.com/doc/master/bundles/DoctrineFixturesBundle/index.html

`composer require --dev doctrine/doctrine-fixtures-bundle`

> creer DataFxitures/AppFixtures.php

`php bin/console doctrine:fixtures:load`

## On veut avoir des fixtures aléatoires

`composer require fzaninotto/faker`
`composer require mbezhanov/faker-provider-collection`

> edit DataFxitures/AppFixtures.php

`php bin/console doctrine:fixtures:load`

`php bin/console doctrine:query:sql 'SELECT * FROM recette'`

## On veut automatiser la mise à jour de la date de création et la création d'un slug

https://symfony.com/doc/current/doctrine/common_extensions.html

`composer require stof/doctrine-extensions-bundle`

> edit packages>stof_doctrine_extensions.yml

> edit Entity/Recette.php Add \* @Gedmo\Timestampable(on="create") and slug props

> comment DataFxitures/AppFixtures.php setDateCreation

`php bin/console doctrine:query:sql 'SELECT * FROM recette'`

## On veut afficher les recettes et navigier

> edit controller pour ajouter le findAll et le show

https://symfony.com/doc/current/doctrine.html#fetching-objects-from-the-database

> edit templates/base pour rajouter bootstrap
> edit templates/default/index pour afficher chaque recette

https://twig.symfony.com/doc/2.x/tags/for.html

> add {{ path('recette_show', {'slug': recette.slug}) }}
 > https://symfony.com/doc/current/templating.html#linking-to-pages

## passage en api rest

> composer require api

https://api-platform.com/docs/core/getting-started/

rajouter du mapping sur les entity

> - @ApiResource

curl -X GET "http://127.0.0.1:8000/api/recettes" -H "accept: application/ld+json"

## ajouter des tests

> composer require --dev symfony/phpunit-bridge
> ./bin/phpunit

https://symfony.com/doc/current/testing.html

> composer require --dev symfony/browser-kit symfony/css-selector

## avoir une mise en forme + sympa

principe d'héritage avec twig : ajout d'un layout avec block body > main, override title

## créer un formulaire

composer require symfony/swiftmailer-bundle symfony/form symfony/validator

https://symfony.com/doc/current/forms.html
pour un + beau rendu

> https://symfony.com/doc/current/form/bootstrap4.html

## envoyer un mail

editer mailer conf in .env

> gmail://username:password@localhost

> SEND_TO=toto@gmail.com

- autoriser https://myaccount.google.com/lesssecureapps

https://symfony.com/doc/current/email.html

## avoir des logs

https://symfony.com/doc/current/logging.html

`https://symfony.com/doc/current/logging.html`

## avoir un dashboard admin

avec easy admin
`composer require admin`
Open the config/packages/easy_admin.yaml file and add the following configuration:

https://symfony.com/doc/master/bundles/EasyAdminBundle/book/your-first-backend.html

controler l'accès dans security.yaml : https://symfony.com/doc/current/security.html

>        - { path: ^/admin, roles: ROLE_ADMIN }

```
in_memory:
            memory:
                users:
                    ryan:
                        password: ryanpass
                        roles: 'ROLE_USER'
                    admin:
                        password: kitten
                        roles: 'ROLE_ADMIN'
```

## on veut des vraies données

on va scrapper marmitton
on crée une "console command"
https://symfony.com/doc/current/console.html

on va avoir besoin d'un client pour aller récupérer des données "à l'extéieur"

> composer require fabpot/goutte

on scrap en récupérant les champs de la page qui nous interesse

on inject doctrine pour persister les recettes

en bonus une progress bar
https://symfony.com/doc/current/components/console/helpers/progressbar.html

on montre la photo de la recette si le champs img est renseigné
