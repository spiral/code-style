<?php

/**
 * Spiral Framework. Code Style
 *
 * @license MIT
 * @author  Aleksandr Novikov (alexndr-novikov)
 */
declare(strict_types=1);

namespace Spiral\Tests\CodeStyle;

use PHPUnit\Framework\TestCase;

class CodeStyleFixTest extends AbstractCodeStyleTest
{
    public function testFix(): void
    {
        exec('bin/spiral-cs fix ' . $this->getRelativeFilePath(self::NOT_FORMATTED_FILE_NAME));
        $this->assertEquals(
            file_get_contents($this->getTempFilePath('NotFormattedClass.php')),
            file_get_contents($this->getFixturesFilePath('FormattedClass.php')),
        );
    }
}
