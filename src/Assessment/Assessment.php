<?php
declare(strict_types=1);

namespace App\Assessment;

use App\Assessment\Problems\AssessmentCannotBeLocked;
use App\Assessment\Problems\AssessmentCannotBeUnlocked;
use App\Assessment\Problems\NotDescriptiveEnough;
use App\Client;
use App\Standard;
use App\Supervisor;
use DateInterval;
use DateTimeImmutable;

final class Assessment
{
    private AssessmentStatus $status;

    public function __construct(
        public readonly Client $client,
        public readonly Supervisor $supervisor,
        public readonly Standard $standard,
        public readonly DateTimeImmutable $date,
        public readonly Evaluation $evaluation,
    ) {
        $this->status = AssessmentStatus::Open;
    }

    public function isExpired(): bool
    {
        return $this->date->add(new DateInterval('P365D')) < new DateTimeImmutable();
    }

    /**
     * @throws NotDescriptiveEnough
     * @throws AssessmentCannotBeLocked
     */
    public function suspend(string $description): void
    {
        AssessmentCannotBeLocked::whenExpired($this);
        AssessmentCannotBeLocked::whenLocked($this->status);
        NotDescriptiveEnough::whenTooShort($description);
        $this->status = AssessmentStatus::Suspension;
    }

    /**
     * @throws NotDescriptiveEnough
     * @throws AssessmentCannotBeLocked
     */
    public function withdraw(string $description): void
    {
        AssessmentCannotBeLocked::whenExpired($this);
        NotDescriptiveEnough::whenTooShort($description);
        $this->status = AssessmentStatus::Withdrawn;
    }

    /**
     * @throws AssessmentCannotBeUnlocked
     */
    public function unlock(): void
    {
        AssessmentCannotBeUnlocked::whenNotLocked($this->status);
        $this->status = AssessmentStatus::Open;
    }
}
