<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';


Phar::loadPhar(__DIR__ . '/../vendor/php-cs-fixer/shim/php-cs-fixer.phar', 'php-cs-fixer.phar');

require 'phar://php-cs-fixer.phar/vendor/autoload.php';
