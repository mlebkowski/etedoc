<?php
declare(strict_types=1);

namespace App\Authority;

use App\Standard;
use App\Supervisor;
use DateTimeImmutable;
use Webmozart\Assert\Assert;

final readonly class Authority
{
    public function __construct(
        public Supervisor $supervisor,
        public Standard $standard,
        public DateTimeImmutable $since,
        public DateTimeImmutable $until,
    ) {
        Assert::greaterThan($this->until, $this->since);
    }

    public function covers(DateTimeImmutable $date): bool
    {
        return $this->since <= $date && $date <= $this->until;
    }
}
