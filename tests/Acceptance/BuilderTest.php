<?php

declare(strict_types=1);

namespace Spiral\CodeStyle\Tests\Acceptance;

use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;

final class BuilderTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testDisableRisky(): void
    {
        $config = \realpath(__DIR__ . '/Stub/config-no-risky.php');
        $command = "php vendor/bin/php-cs-fixer fix --dry-run --config=$config";

        \exec($command, $output, $status);

        \in_array($status, [0, 8]) or $this->fail(\sprintf(
            "php-cs-fixer failed: %s. \n  %s\n\n%s",
            $status,
            \implode('\n  ', $this->describeFailCommand($status)),
            \implode("\n", $output),
        ));
    }
}
