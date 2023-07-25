<?php
declare(strict_types=1);

namespace App\Assessment;

use App\Assessment\Problems\GracePeriodExpected;
use App\Assessment\Problems\SameStandardRequired;
use App\Clock\Clock;

final readonly class FurtherEvaluationConductor
{
    public function __construct(
        private AssessmentRepository $assessments,
        private Clock $clock,
    ) {
    }

    /**
     * @throws GracePeriodExpected
     * @throws SameStandardRequired
     */
    public function conduct(Assessment $next): void
    {
        $current = $this->assessments->findForClient($next->client);
        if (null === $current) {
            $this->assessments->save($next);
            return;
        }

        if (false === $current->standard->equals($next->standard)) {
            throw SameStandardRequired::ofMismatch($current->standard, $next->standard);
        }

        $gracePeriod = $current->evaluation->gracePeriod();
        if ($current->date->add($gracePeriod) > $this->clock->now()) {
            throw GracePeriodExpected::of($current->date, $gracePeriod, $this->clock->now());
        }

        $this->assessments->replace($current, $next);
    }
}
