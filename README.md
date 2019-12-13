# symfony-shopping-cart-starter

##  Setup
* Clone the project.

* Rename .env.example to .env and add your database credentials.
``` bash
 DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
```
* Install dependencies 
``` bash
composer install
``` 

* Execute migrations.
``` bash
 php bin/console doctrine:migrations:migrate
```

* Run server ([Symfony Local Web Server](https://symfony.com/doc/current/setup/symfony_server.html))

``` bash
 symfony server:start
```
* Test
``` bash
 php bin/phpunit
```