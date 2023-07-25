<?php
declare(strict_types=1);

namespace App\Authority;

use App\Supervisor;

interface AuthorityRepository
{
    public function forSupervisor(Supervisor $supervisor): array;
}
