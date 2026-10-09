<?php

declare(strict_types=1);

namespace Spiral\CodeStyle\Tests\Acceptance;

use Testo\Assert;
use Testo\Test;

#[Test]
final class StyleTest extends TestCase
{
    public function testStyled(): void
    {
        $dir = \realpath(__DIR__ . '/Stub/Styled');
        $command = "php vendor/bin/php-cs-fixer fix --allow-unsupported-php-version=yes --dry-run --diff --format=json $dir";

        \exec($command, $output, $status);

        \in_array($status, [0, 8]) or Assert::fail(\sprintf(
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
            Assert::true(true);
            return;
        }

        Assert::fail(\sprintf(
            "Some nominal stub files were changed: \n\n%s",
            \implode("\n", \array_map(
                static fn(array $file) => $file['diff'],
                $result['files'],
            )),
        ));
    }

    public function testUnstyledIsFixedToStyled(): void
    {
        $dir = \sys_get_temp_dir() . '/spiral-code-style-' . \uniqid();
        \mkdir($dir);
        $file = $dir . '/Imports.php';
        \copy(__DIR__ . '/Stub/Unstyled/Imports.php', $file);

        try {
            $command = "php vendor/bin/php-cs-fixer fix --allow-unsupported-php-version=yes --using-cache=no $file";
            \exec($command, $output, $status);

            $status === 0 or Assert::fail(\sprintf(
                "php-cs-fixer failed: %s. \n  %s\n\n%s",
                $status,
                \implode('\n  ', $this->describeFailCommand($status)),
                \implode("\n", $output),
            ));

            Assert::same(\file_get_contents($file), \file_get_contents(__DIR__ . '/Stub/Styled/Imports.php'));
        } finally {
            \unlink($file);
            \rmdir($dir);
        }
    }
}
