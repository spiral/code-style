<?php

declare(strict_types=1);

namespace Spiral\CodeStyle\Tests\Acceptance\Stub\Styled;

use const Spiral\CodeStyle\Tests\VALUE;
use Spiral\CodeStyle\RulesInterface;
use function Spiral\CodeStyle\Tests\helper;
use Spiral\CodeStyle\Rules\DefaultRules;
use Spiral\CodeStyle\Builder;

final class Imports
{
    public function __construct(
        private readonly RulesInterface $rules = new \Spiral\CodeStyle\Rules\DefaultRules(),
    ) {}

    public function build(\Spiral\CodeStyle\Builder $builder): \Countable
    {
        helper($this->rules, VALUE);

        return new \ArrayObject([$builder]);
    }
}
