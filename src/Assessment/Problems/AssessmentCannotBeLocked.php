<?php
declare(strict_types=1);

namespace App\Assessment\Problems;

use App\Assessment\Assessment;
use App\Assessment\AssessmentStatus;
use Exception;

final class AssessmentCannotBeLocked extends Exception
{
    /**
     * @throws AssessmentCannotBeLocked
     */
    public static function whenExpired(Assessment $assessment): void
    {
        if ($assessment->isExpired()) {
            throw new self('Expired assessment cannot be locked');
        }
    }

    /**
     * @throws AssessmentCannotBeLocked
     */
    public static function whenLocked(AssessmentStatus $status): void
    {
        if ($status->isLocked()) {
            throw new self('It is not possible to lock an assessment that is currently locked');
        }
    }
}
