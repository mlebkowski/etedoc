<?php
declare(strict_types=1);

namespace App\Assessment\Requirements\Problems;

use App\Authority\Authority;
use App\Standard;
use DateTimeImmutable;
use Exception;

final class AuthorityRequirementUnmet extends Exception
{
    public static function ofExpired(Authority $authority, DateTimeImmutable $now): self
    {
        return new self(sprintf(
            'Required authority for subject "%s" validity period %s - %s does not contain %s',
            $authority->standard,
            $authority->since->format('Y-m-d'),
            $authority->until->format('Y-m-d'),
            $now->format('Y-m-d'),
        ));
    }

    public static function ofMissing(Standard $standard): self
    {
        return new self(sprintf('Missing required authority for standard "%s"', $standard));
    }
}
