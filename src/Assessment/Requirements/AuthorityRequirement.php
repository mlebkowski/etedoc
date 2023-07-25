<?php
declare(strict_types=1);

namespace App\Assessment\Requirements;

use App\Assessment\Assessment;
use App\Assessment\Requirements\Problems\AuthorityRequirementUnmet;
use App\Authority\Authority;
use App\Authority\AuthorityRepository;
use loophp\collection\Collection;

final readonly class AuthorityRequirement implements Requirement
{
    public function __construct(private AuthorityRepository $authorities)
    {
    }

    /**
     * @throws AuthorityRequirementUnmet
     */
    public function ensureSatisfied(Assessment $assessment): void
    {
        $authorities = $this->authorities->forSupervisor($assessment->supervisor);
        $authority = Collection::fromIterable($authorities)->find(
            callbacks: static fn (Authority $authority) => $authority->standard->equals($assessment->standard),
        );

        if (null === $authority) {
            throw AuthorityRequirementUnmet::ofMissing($assessment->standard);
        }

        if (false === $authority->covers($assessment->date)) {
            throw AuthorityRequirementUnmet::ofExpired($authority, $assessment->date);
        }
    }
}
