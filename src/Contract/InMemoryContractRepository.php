<?php
declare(strict_types=1);

namespace App\Contract;

use App\Client;
use loophp\collection\Collection;

final readonly class InMemoryContractRepository implements ContractRepository
{
    public static function of(Contract ...$contracts): self
    {
        return new self($contracts);
    }

    private function __construct(private array $contracts)
    {
    }

    public function forClient(Client $client): array
    {
        return Collection::fromIterable($this->contracts)
            ->filter(static fn (Contract $contract) => $contract->client === $client)
            ->all();
    }
}
