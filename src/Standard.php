<?php
declare(strict_types=1);

namespace App;

use Stringable;
use Symfony\Component\Uid\Ulid;

final readonly class Standard implements Stringable
{
    public string $id;

    public function __construct(public string $name)
    {
        $this->id = Ulid::generate();
    }

    public function equals(self $other): bool
    {
        return $this->id === $other->id;
    }

    public function __toString(): string
    {
        return "[$this->id] $this->name";
    }
}
