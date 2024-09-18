<?php

declare(strict_types=1);

namespace Spiral\CodeStyle\Tests\Acceptance\Stub\Styled;

final class Attributes
{
    private function __construct(
        #[Marshal(name: 'second')]
        public readonly string $second,
        #[Marshal(name: 'minute')]
        public readonly string $minute,
        #[Marshal(name: 'hour')]
        public readonly string $hour,
        #[Marshal(name: 'day_of_month')]
        public readonly string $dayOfMonth,
        #[Marshal(name: 'month')]
        public readonly string $month,
        #[Marshal(name: 'year')]
        public readonly string $year,
    ) {}

    private function sameLine(
        #[Marshal] string $dayOfWeek,
        #[Marshal] string $comment,
    ): void {}
}
