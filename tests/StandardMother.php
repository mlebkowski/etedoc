<?php
declare(strict_types=1);

namespace App;

final readonly class StandardMother
{
    public static function some(): Standard
    {
        return new Standard('lorem ipsum');
    }
}
