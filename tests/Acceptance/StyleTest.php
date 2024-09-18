<?php

declare(strict_types=1);

namespace Spiral\CodeStyle\Tests\Acceptance;

use PHPUnit\Framework\TestCase;

final class StyleTest extends TestCase
{
    private const CS_STATUSES = [
        1 => 'General error (or PHP minimal requirement not matched).',
        4 => 'Some files have invalid syntax (only in dry-run mode).',
        8 => 'Some files need fixing (only in dry-run mode).',
        16 => 'Configuration error of the application.',
        32 => 'Configuration error of a Fixer.',
        64 => 'Exception raised within the application.',
    ];

    public function testStyled(): void
    {
        $dir = \realpath(__DIR__ . '/Stub/Styled');
        $command = "php vendor/bin/php-cs-fixer fix --dry-run --diff --format=json $dir";

        \exec($command, $output, $status);

        \in_array($status, [0, 8]) or $this->fail(\sprintf(
            "php-cs-fixer failed: %s. \n  %s\n\n%s",
            $status,
            \implode('\n  ', $this->describeFailCommand($status)),
            \implode("\n", $output),
        ));

        /**
         * @var array{
         *     about: non-empty-string,
         *     files: list<array{
         *         name: non-empty-string,
         *         diff: non-empty-string,
         *     }>,
         *     time: array{total: float},
         *     memory: positive-int
         * } $result
         */
        $result = \json_decode(\implode('', $output), true, 512, \JSON_THROW_ON_ERROR);

        // No files to fix
        if ($result['files'] === []) {
            $this->assertTrue(true);
            return;
        }

        $this->fail(\sprintf(
            "Some nominal stub files were changed: \n\n%s",
            \implode("\n", \array_map(
                static fn(array $file) => $file['diff'],
                $result['files'],
            )),
        ));
    }

    private function describeFailCommand(int $code): array
    {
        $result = [];
        foreach (self::CS_STATUSES as $mask => $description) {
            $code & $mask and $result[] = $description;
        }

        return $result;
    }
}
