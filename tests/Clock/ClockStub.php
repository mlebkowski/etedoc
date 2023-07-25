<?php
declare(strict_types=1);

namespace App\Clock;

use DateTimeImmutable;

final readonly class ClockStub implements Clock
{
    public static function of(DateTimeImmutable $now): self
    {
        return new self($now);
    }

    private function __construct(private DateTimeImmutable $now)
    {
    }

    public function now(): DateTimeImmutable
    {
        return $this->now;
    }
}
