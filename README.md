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
composer require --dev spiral/code-style
``` 

#### Check the code
```

#vendor/bin/spiral-cs check <dir1> <dir2> <file1>....
vendor/bin/spiral-cs check src tests
```

#### Automatically fix the code style

```
#vendor/bin/spiral-cs fix <dir1> <dir2> <file1>....
vendor/bin/spiral-cs fix src tests
```
