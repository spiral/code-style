<?php

declare(strict_types=1);

namespace Spiral\CodeStyle\Tests\Acceptance;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    private const CS_STATUSES = [
        1 => 'General error (or PHP minimal requirement not matched).',
        4 => 'Some files have invalid syntax (only in dry-run mode).',
        8 => 'Some files need fixing (only in dry-run mode).',
        16 => 'Configuration error of the application.',
        32 => 'Configuration error of a Fixer.',
        64 => 'Exception raised within the application.',
    ];

    protected function describeFailCommand(int $code): array
    {
        $result = [];
        foreach (self::CS_STATUSES as $mask => $description) {
            $code & $mask and $result[] = $description;
        }

        return $result;
    }
}
