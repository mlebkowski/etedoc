<?php
declare(strict_types=1);

namespace App\Clock;

use DateTimeImmutable;

final readonly class RealClock implements Clock
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
