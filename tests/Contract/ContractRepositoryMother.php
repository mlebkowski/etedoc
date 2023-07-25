<?php
declare(strict_types=1);

namespace App\Contract;

final readonly class ContractRepositoryMother
{
    public static function some(): ContractRepository
    {
        return InMemoryContractRepository::of();
    }
}
