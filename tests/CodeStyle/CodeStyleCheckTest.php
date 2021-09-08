<?php

/**
 * Spiral Framework. Code Style
 *
 * @license MIT
 * @author  Aleksandr Novikov (alexndr-novikov)
 */
declare(strict_types=1);

namespace Spiral\Tests\CodeStyle;

class CodeStyleCheckTest extends AbstractCodeStyleTest
{
    public function testWrongFormattedClass(): void
    {
        $out = $this->execCommand([
            'bin/spiral-cs',
            'check',
            $this->getRelativeFilePath(self::NOT_FORMATTED_FILE_NAME)
        ]);

        $this->assertArrayHasKey(0, $out);
        $this->assertGreaterThan(1, count($out));
        $this->assertNotSame($out[0], 'No codestyle issues');
    }

    public function testWellFormattedClass(): void
    {
        $out = $this->execCommand([
            'bin/spiral-cs',
            'check',
            $this->getRelativeFilePath(self::FORMATTED_FILE_NAME)
        ]);

        $this->assertArrayHasKey(0, $out);
        $this->assertEquals($out[0], 'No codestyle issues');
    }

    public function testIgnoredFilesShouldBeSkipped()
    {
        $out = $this->execCommand([
            'bin/spiral-cs',
            'check',
            '--ignore=NotFormattedClass.php',
            'tests/fixtures/temp/'
        ]);

        $this->assertArrayHasKey(0, $out);
        $this->assertEquals($out[0], 'No codestyle issues');
    }
}
