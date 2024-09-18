<?php

declare(strict_types=1);

namespace Spiral\CodeStyle\Tests\Unit;

use PhpCsFixer\Config;
use PHPUnit\Framework\TestCase;
use Spiral\CodeStyle\Builder;

final class BuilderTest extends TestCase
{
    public function testBuilderCreatesConfig(): void
    {
        $config = Builder::create()
            ->include(__DIR__ . '/../../src')
            ->include(__FILE__)
            ->build();

        self::assertInstanceOf(Config::class, $config);
    }

    public function testConfigureCacheFile(): void
    {
        $newFile = __FILE__ . '.cache';

        $config = Builder::create()
            ->include(__DIR__)
            ->cache($newFile)
            ->build();

        self::assertSame($newFile, $config->getCacheFile());
    }

    public function testRiskyMode(): void
    {
        $config = Builder::create()->include(__DIR__)->allowRisky(false)->build();

        self::assertFalse($config->getRiskyAllowed());
    }

    public function testRiskyModeDefault(): void
    {
        $config = Builder::create()->include(__DIR__)->build();

        self::assertTrue($config->getRiskyAllowed());
    }
}
