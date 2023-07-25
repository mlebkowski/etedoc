<?php
declare(strict_types=1);

namespace App\Authority;

use App\Supervisor;
use loophp\collection\Collection;

final readonly class InMemoryAuthorityRepository implements AuthorityRepository
{
    public static function of(Authority ...$authorities): self
    {
        return new self($authorities);
    }

    private function __construct(private array $authorities)
    {
    }

    public function forSupervisor(Supervisor $supervisor): array
    {
        return Collection::fromIterable($this->authorities)
            ->filter(static fn (Authority $authority) => $authority->supervisor->equals($supervisor))
            ->all();
    }
}
