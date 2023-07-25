<?php
declare(strict_types=1);

namespace App\Assessment\Problems;

use App\Assessment\AssessmentStatus;
use Exception;

final class AssessmentCannotBeUnlocked extends Exception
{
    /**
     * @throws AssessmentCannotBeUnlocked
     */
    public static function whenNotLocked(AssessmentStatus $status): void
    {
        if (false === $status->isLocked()) {
            throw new self('Assessment needs to be locked to unlock');
        }
    }
}
