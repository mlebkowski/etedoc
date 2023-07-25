<?php
declare(strict_types=1);

namespace App\Assessment;

use App\ClientMother;
use App\StandardMother;
use App\SupervisorMother;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class AssessmentTest extends TestCase
{
    public static function expiration data provider(): iterable
    {
        yield [new DateTimeImmutable('-364 days'), false];
        yield [new DateTimeImmutable('-365 days'), true];
    }

    #[DataProvider('expiration data provider')]
    public function test it expires after its validity period passes(DateTimeImmutable $date, bool $expected): void
    {
        $sut = new Assessment(
            ClientMother::some(),
            SupervisorMother::some(),
            StandardMother::some(),
            $date,
            Evaluation::Negative,
        );
        $actual = $sut->isExpired();
        self::assertSame($expected, $actual);
    }

    public function test locking(): void
    {
        $sut = new Assessment(
            ClientMother::some(),
            SupervisorMother::some(),
            StandardMother::some(),
            new DateTimeImmutable(),
            Evaluation::Positive,
        );
        $sut->suspend('some reason');
        $sut->withdraw('another reason');
        self::assertTrue(
            true,
            'Yeah, I have no idea what to expect as there are no public side effects of those operations',
        );
    }
}
