<?php
declare(strict_types=1);

namespace App\Assessment\Requirements;

use App\Assessment\Assessment;
use App\Assessment\Requirements\Problems\ValidContractMissing;
use App\Contract\Contract;
use App\Contract\ContractRepository;
use loophp\collection\Collection;

final readonly class ContractRequirement implements Requirement
{
    public function __construct(private ContractRepository $contracts)
    {
    }

    /**
     * @throws ValidContractMissing
     */
    public function ensureSatisfied(Assessment $assessment): void
    {
        $contracts = $this->contracts->forClient($assessment->client);
        $contract = Collection::fromIterable($contracts)->find(
            callbacks: static fn (Contract $contract) => $contract->supervisor === $assessment->supervisor,
        );

        if (false === $contract instanceof Contract) {
            throw ValidContractMissing::ofMissing();
        }

        if (false === $contract->status->isActive()) {
            throw ValidContractMissing::ofInactive();
        }
    }
}
