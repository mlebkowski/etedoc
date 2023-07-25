<?php
declare(strict_types=1);

namespace App;

use Symfony\Component\Uid\Ulid;

final readonly class Client
{
    public string $id;

    public function __construct()
    {
        $this->id = Ulid::generate();
    }
}
