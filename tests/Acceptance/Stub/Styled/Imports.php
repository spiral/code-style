<?php

declare(strict_types=1);

namespace Spiral\CodeStyle\Tests\Acceptance\Stub\Styled;

use Spiral\CodeStyle\Builder;
use Spiral\CodeStyle\Rules\DefaultRules;
use Spiral\CodeStyle\RulesInterface;

use function Spiral\CodeStyle\Tests\helper;

use const Spiral\CodeStyle\Tests\VALUE;

final class Imports
{
    public function __construct(
        private readonly RulesInterface $rules = new DefaultRules(),
    ) {}

    public function build(Builder $builder): \Countable
    {
        helper($this->rules, VALUE);

        return new \ArrayObject([$builder]);
    }
}
