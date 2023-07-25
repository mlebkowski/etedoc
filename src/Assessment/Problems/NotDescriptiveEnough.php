<?php
declare(strict_types=1);

namespace App\Assessment\Problems;

use Exception;

final class NotDescriptiveEnough extends Exception
{
    /**
     * @throws NotDescriptiveEnough
     */
    public static function whenTooShort(string $reason): void
    {
        if (strlen($reason) < 10) {
            throw new self("Given reason is not descriptive enough: '$reason'");
        }
    }
}
