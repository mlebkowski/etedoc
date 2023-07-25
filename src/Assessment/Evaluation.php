<?php
declare(strict_types=1);

namespace App\Assessment;

use DateInterval;

enum Evaluation
{
    case Positive;
    case Negative;

    public function gracePeriod(): DateInterval
    {
        return match ($this) {
            self::Positive => new DateInterval('P180D'),
            self::Negative => new DateInterval('P30D'),
        };
    }
}
