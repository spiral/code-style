<?php

declare(strict_types=1);

namespace Spiral\CodeStyle\Tests\Unit;

use PhpCsFixer\Config;
use Spiral\CodeStyle\Builder;
use Testo\Assert;
use Testo\Assert\ExpectException;
use Testo\Test;

#[Test]
final class BuilderTest
{
    public function testBuilderCreatesConfig(): void
    {
        $config = Builder::create()
            ->include(__DIR__ . '/../../src')
            ->include(__FILE__)
            ->build();

        Assert::instanceOf($config, Config::class);
    }

    public function testConfigureCacheFile(): void
    {
        $newFile = __FILE__ . '.cache';

        $config = Builder::create()
            ->include(__DIR__)
            ->cache($newFile)
            ->build();

        Assert::same($config->getCacheFile(), $newFile);
    }

    public function testRiskyMode(): void
    {
        $config = Builder::create()->include(__DIR__)->allowRisky(false)->build();

        Assert::false($config->getRiskyAllowed());
    }

    public function testRiskyModeDefault(): void
    {
        $config = Builder::create()->include(__DIR__)->build();

        Assert::true($config->getRiskyAllowed());
    }

    #[ExpectException(\InvalidArgumentException::class)]
    public function testIncludeNonExistingPath(): void
    {
        Builder::create()->include('./non/existing/path');
    }

    #[ExpectException(\InvalidArgumentException::class)]
    public function testExcludeNonExistingPath(): void
    {
        Builder::create()->exclude('./non/existing/path');
    }
}
