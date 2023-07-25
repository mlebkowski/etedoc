<?php
declare(strict_types=1);

namespace App\Assessment\Problems;

use App\Standard;
use Exception;

final class SameStandardRequired extends Exception
{
    public static function ofMismatch(Standard $previous, Standard $next): self
    {
        return new self(sprintf(
            <<<EOF
            The current assessment was conducted in "%s" standard,
             you cannot replace it with one obtained with "%s" standard
            EOF,
            $previous,
            $next,
        ));
    }
}
