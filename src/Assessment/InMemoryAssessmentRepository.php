<?php
declare(strict_types=1);

namespace App\Assessment;

use App\Client;
use loophp\collection\Collection;

final class InMemoryAssessmentRepository implements AssessmentRepository
{
    public static function of(Assessment ...$assessments): self
    {
        return new self(
            Collection::fromIterable($assessments)
                ->map(static fn (Assessment $assessment) => [$assessment->client->id, $assessment])
                ->unpack()
                ->all(false),
        );
    }

    private function __construct(private array $assessments)
    {
    }

    public function replace(Assessment $previous, Assessment $current): void
    {
        assert($this->assessments[$current->client->id] === $previous);
        $this->assessments[$current->client->id] = $current;
    }

    public function save(Assessment $assessment): void
    {
        assert(false === isset($this->assessments[$assessment->client->id]));
        $this->assessments[$assessment->client->id] = $assessment;
    }

    public function findForClient(Client $client): ?Assessment
    {
        return $this->assessments[$client->id] ?? null;
    }
}
