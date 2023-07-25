<?php
declare(strict_types=1);

namespace App\Assessment\Problems;

use DateInterval;
use DateTimeImmutable;
use Exception;

final class GracePeriodExpected extends Exception
{
    public static function of(DateTimeImmutable $date, DateInterval $gracePeriod, DateTimeImmutable $now): self
    {
        return new self(
            sprintf(
                'A %d day grace period required after the previous assessment at "%s" (%d days left)',
                $gracePeriod->days,
                $date->format('Y-m-d'),
                $gracePeriod->days - $now->diff($date)->days,
            ),
        );
    }
}
