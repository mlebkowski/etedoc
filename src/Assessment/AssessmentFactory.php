<?php
declare(strict_types=1);

namespace App\Assessment;

use App\Assessment\Requirements\AuthorityRequirement;
use App\Assessment\Requirements\ContractRequirement;
use App\Assessment\Requirements\Problems\AuthorityRequirementUnmet;
use App\Assessment\Requirements\Problems\ValidContractMissing;
use App\Client;
use App\Clock\Clock;
use App\Standard;
use App\Supervisor;

final readonly class AssessmentFactory
{
    public function __construct(
        private AuthorityRequirement $authorityRequirement,
        private ContractRequirement $contractRequirement,
        private Clock $clock,
    ) {
    }

    /**
     * @throws AuthorityRequirementUnmet
     * @throws ValidContractMissing
     */
    public function make(Client $client, Supervisor $supervisor, Standard $standard, Evaluation $evaluation): Assessment
    {
        $assessment = new Assessment($client, $supervisor, $standard, $this->clock->now(), $evaluation);
        $this->authorityRequirement->ensureSatisfied($assessment);
        $this->contractRequirement->ensureSatisfied($assessment);
        return $assessment;
    }
}
