### PHP Code Fixer

1. Standard Code Style
- https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc.md#1-introduction
- https://github.com/PHP-CS-Fixer/PHP-CS-Fixer
- https://packagist.org/packages/friendsofphp/php-cs-fixer
```bash 
$ composer require friendsofphp/php-cs-fixer
$ mkdir -p tools/php-cs-fixer
$ composer require --working-dir=tools/php-cs-fixer friendsofphp/php-cs-fixer
$ tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src
$ tools/php-cs-fixer/vendor/bin/php-cs-fixer fix app
```