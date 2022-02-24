## Skeleton (Symfony Demo Application) 

The "Skeleton" is a reference application created to show how
to develop applications following the Symfony Best Practices.

Requirements
------------

* PHP 8.0.2 or higher;
* PDO-SQLite PHP extension enabled;
* and the usual Symfony application requirements.

Installation
------------

```bash
$ git clone https://github.com/trngl8/symfony-skeleton-demo.git my_project
$ cd my_project/
$ composer install 
```

Users manipulation
--------
List:
```bash
$ php bin/console app:user:list 
```


Creation:
```bash
$ php bin/console app:user:add 
```
