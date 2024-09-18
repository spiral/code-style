<?php

declare(strict_types=1);

namespace Spiral\CodeStyle;

/**
 * @internal might be changed in the future
 */
interface RulesInterface
{
    /**
     * @return array<non-empty-string, array<non-empty-string, mixed>|bool>
     */
    public function getRules(): array;
}
