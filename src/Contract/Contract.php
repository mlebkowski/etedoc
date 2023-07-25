<?php
declare(strict_types=1);

namespace App\Contract;

use App\Client;
use App\Supervisor;

final readonly class Contract
{
    public function __construct(
        public Client $client,
        public Supervisor $supervisor,
        public ContractStatus $status,
    ) {
    }
}
