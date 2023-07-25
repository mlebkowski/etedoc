<?php
declare(strict_types=1);

namespace App\Assessment\Requirements;

use App\Assessment\Assessment;

interface Requirement
{
    public function ensureSatisfied(Assessment $assessment): void;
}
