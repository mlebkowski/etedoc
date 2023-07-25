<?php
declare(strict_types=1);

namespace App;

final readonly class SupervisorMother
{
    public static function some(): Supervisor
    {
        return new Supervisor();
    }
}
