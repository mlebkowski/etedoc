<?php
declare(strict_types=1);

namespace App\Assessment;

enum AssessmentStatus
{
    case Open;
    case Suspension;
    case Withdrawn;

    public function isLocked(): bool
    {
        return in_array($this, [self::Suspension, self::Withdrawn], true);
    }
}
