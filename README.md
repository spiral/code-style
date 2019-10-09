# Codestyle presets for Spiral repositories

This repository contains ruleset for static analyses tools.
Currently supported:
- [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer/)
- [PHP CS Fixer](https://cs.symfony.com/)

Current codestyle is PSR-12.

PHP Codesniffer ruleset is located in `config/ruleset.xml`.

PHP CS Fixer ruleset is located in `config/.php_cs`

To apply it in your project do the following: 

#### Install the package

```
composer require --dev spiral/cs
``` 

#### Check the code
```
vendor/bin/phpcs --standard=vendor/spiral/cs/config/ruleset.xml src <directory_1> ...
```

#### Automatically fix the code style

```
vendor/bin/phpcbf --standard=vendor/spiral/cs/config/ruleset.xml  src/
vendor/bin/php-cs-fixer fix --config=vendor/spiral/cs/config/.php_cs src/
```
