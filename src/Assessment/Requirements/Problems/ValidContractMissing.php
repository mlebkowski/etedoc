<?php
declare(strict_types=1);

namespace App\Assessment\Requirements\Problems;

use Exception;

final class ValidContractMissing extends Exception
{
    public static function ofInactive(): self
    {
        return new self('Contract between the client and supervisor is inactive');
    }

    public static function ofMissing(): self
    {
        return new self('Contract between the client and supervisor is missing');
    }
}
