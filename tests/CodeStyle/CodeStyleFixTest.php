<?php

/**
 * Spiral Framework. Code Style
 *
 * @license MIT
 * @author  Aleksandr Novikov (alexndr-novikov)
 */
declare(strict_types=1);

namespace Spiral\Tests\CodeStyle;

class CodeStyleFixTest extends AbstractCodeStyleTest
{
    public function testFix(): void
    {
        $this->execCommand([
            'bin/spiral-cs',
            'fix',
            $this->getRelativeFilePath(self::NOT_FORMATTED_FILE_NAME)
        ]);

        $this->assertFileEquals(
            $this->getFixturesFilePath(self::FORMATTED_FILE_NAME),
            $this->notFormattedClassFilePath
        );
    }

    public function testFixWithAlternateConfig(): void
    {
        $this->execCommand([
            'bin/spiral-cs',
            'fix',
            '--config',
            $this->getFixturesFilePath('.php_cs_alternate'),
            $this->getRelativeFilePath(self::NOT_FORMATTED_FILE_NAME)
        ]);

        $this->assertFileEquals(
            $this->getFixturesFilePath('AlternateFormattedClass.php'),
            $this->notFormattedClassFilePath
        );
    }
}
