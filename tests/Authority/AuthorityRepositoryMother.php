<?php
declare(strict_types=1);

namespace App\Authority;

final readonly class AuthorityRepositoryMother
{
    public static function some(): AuthorityRepository
    {
        return InMemoryAuthorityRepository::of();
    }
}
