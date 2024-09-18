# Code style presets for Spiral components

This repository contains ruleset for [PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) based on PER-2.

## Installation and configuration

Install the package via composer:

```
composer require --dev spiral/code-style
```

[![PHP](https://img.shields.io/packagist/php-v/spiral/code-style.svg?style=flat-square&logo=php)](https://packagist.org/packages/spiral/code-style)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/spiral/code-style.svg?style=flat-square&logo=packagist)](https://packagist.org/packages/spiral/code-style)
[![License](https://img.shields.io/packagist/l/spiral/code-style.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/spiral/code-style.svg?style=flat-square)](https://packagist.org/packages/spiral/code-style)

Create a configuration file `.php-cs-fixer.dist.php` in the root of your project:

```php
<?php declare(strict_types=1);

require_once 'vendor/autoload.php';

return \Spiral\CodeStyle\Builder::create()
    ->include(__DIR__ . '/src')
    ->include(__FILE__)
    ->build();
```

Feel free to adjust the paths to include/exclude files and directories.

## Usage

To more convenient usage, you can add the following commands to the `scripts` section of the `composer.json` file:

```json
{
    "scripts": {
        "cs:diff": "php-cs-fixer fix --dry-run -v --diff",
        "cs:fix": "php-cs-fixer fix -v"
    }
}
```

Now you can run the following commands:

```bash
composer cs:diff
composer cs:fix
```

## CI integration

If you want to integrate code style check into CI, add the following step to your GitHub Actions configuration file:

```yaml
on:
  push:
    branches:
      - '*'

name: Check Code Style

jobs:
  cs-fix:
    uses: spiral/gh-actions/.github/workflows/cs.yml@master
```

If you want GitHub Actions to automatically fix the found errors, add the following step:

```yaml
on:
    push:
        branches:
            - '*'

name: Fix Code Style

jobs:
    cs-fix:
        permissions:
            contents: write
        uses: spiral/gh-actions/.github/workflows/cs-fix.yml@master
```
