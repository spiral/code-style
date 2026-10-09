<?php

declare(strict_types=1);

use Testo\Application\Config\ApplicationConfig;
use Testo\Application\Config\FinderConfig;
use Testo\Application\Config\SuiteConfig;

require_once __DIR__ . '/tests/bootstrap.php';

return new ApplicationConfig(
    src: ['src'],
    suites: [
        new SuiteConfig(
            name: 'Unit',
            location: ['tests/Unit'],
        ),
        new SuiteConfig(
            name: 'Acceptance',
            location: new FinderConfig(
                include: ['tests/Acceptance'],
                // php-cs-fixer input files, not tests: Styled/ and Unstyled/ declare the same classes
                exclude: ['tests/Acceptance/Stub'],
            ),
        ),
    ],
);
