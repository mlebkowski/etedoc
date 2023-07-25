<?php
declare(strict_types=1);

namespace App\Contract;

use App\Client;

interface ContractRepository
{
    public function forClient(Client $client): array;
}
