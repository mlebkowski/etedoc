<?php
declare(strict_types=1);

namespace App\Contract;

enum ContractStatus
{
    case Active;
    case Inactive;

    public function isActive(): bool
    {
        return $this === self::Active;
    }
}
