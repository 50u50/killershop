# Installation:

>[MySQL client]

```create database <name>(e.g. api)```

>[console]
```
git clone https://github.com/50u50/killershop.git
cd killershop
composer install
```
set you MySQL credentials in Symfony config

>[console]
````
bin/console server:start
bin/console doctrine:migrations:migrate
````

API documentation should be available at:
http://127.0.0.1:8000/api/doc

First priority @todo:

 - Adding Unit tests;
 - Fixing most annoying/visible bugs;
 - General refactoring/optimization;
 - Adding auth/orization/entication

####Killer shop - to stability and beyond!
