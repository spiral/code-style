<?php

declare(strict_types=1);

namespace Spiral\Tests\CodeStyle;

use PHPUnit\Framework\TestCase;

abstract class AbstractCodeStyleTest extends TestCase
{
    protected const NOT_FORMATTED_FILE_NAME = 'NotFormattedClass.php';
    protected const FORMATTED_FILE_NAME = 'FormattedClass.php';

    protected $formattedClassFilePath;
    protected $notFormattedClassFilePath;

    protected $tempDir = __DIR__ . '/../fixtures/temp/';

    public function setUp(): void
    {
        copy(
            $this->getFixturesFilePath(self::NOT_FORMATTED_FILE_NAME),
            $this->getTempFilePath(self::NOT_FORMATTED_FILE_NAME)
        );
        copy(
            $this->getFixturesFilePath(self::FORMATTED_FILE_NAME),
            $this->getTempFilePath(self::FORMATTED_FILE_NAME)
        );

        $this->notFormattedClassFilePath = realpath($this->getTempFilePath(self::NOT_FORMATTED_FILE_NAME));
        $this->formattedClassFilePath = realpath($this->getTempFilePath(self::FORMATTED_FILE_NAME));
    }

    protected function tearDown(): void
    {

    }

    protected function getFixturesFilePath(string $fileName): string
    {
        return __DIR__ . '/../fixtures/' . $fileName;
    }

    protected function getTempFilePath(string $fileName): string
    {
        return $this->tempDir . $fileName;
    }

    protected function getRelativeFilePath(string $fileName): string
    {
        return 'tests/fixtures/temp/' . $fileName;
    }

    /**
     * @param string|array $command
     * @return array
     */
    protected function execCommand($command)
    {
        $output = [];

        $command = is_array($command) ? implode(' ', $command) : $command;

        exec($command, $output);

        return $output;
    }
}
