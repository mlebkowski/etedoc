<?php
declare(strict_types=1);

namespace App;

final readonly class ClientMother
{
    public static function some(): Client
    {
        return new Client();
    }
}
