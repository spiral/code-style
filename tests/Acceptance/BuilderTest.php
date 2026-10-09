<?php

declare(strict_types=1);

namespace Spiral\CodeStyle\Tests\Acceptance;

use Testo\Assert;
use Testo\Assert\ExpectNoAssertions;
use Testo\Test;

#[Test]
final class BuilderTest extends TestCase
{
    #[ExpectNoAssertions]
    public function testDisableRisky(): void
    {
        $config = \realpath(__DIR__ . '/Stub/config-no-risky.php');
        $command = "php vendor/bin/php-cs-fixer fix --allow-unsupported-php-version=yes --dry-run --config=$config";

        \exec($command, $output, $status);

        \in_array($status, [0, 8]) or Assert::fail(\sprintf(
            "php-cs-fixer failed: %s. \n  %s\n\n%s",
            $status,
            \implode('\n  ', $this->describeFailCommand($status)),
            \implode("\n", $output),
        ));
    }
}
