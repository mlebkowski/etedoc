<?php
declare(strict_types=1);

namespace App;

use Symfony\Component\Uid\Ulid;

final readonly class Supervisor
{
    public string $id;

    public function __construct()
    {
        $this->id = Ulid::generate();
    }

    public function equals(self $other): bool
    {
        return $this->id === $other->id;
    }
}
