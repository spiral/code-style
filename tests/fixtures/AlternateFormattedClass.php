<?php

/**
 * Spiral Framework. Code style fixer
 * @license MIT
 */

declare(strict_types=1);

namespace Spiral\Tests\fixtures;

use PHPUnit\Framework\TestCase;

class NotFormattedClass
{
    private $property;
    private $data = array();

    public function setUp(): void
    {
        $this->property = "some string";
    }

    public function testWrongFormattedClass()
    {
        $this->assertEquals();
    }
}
