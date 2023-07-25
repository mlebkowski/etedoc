<?php
declare(strict_types=1);

namespace App\Assessment;

use App\Assessment\Problems\GracePeriodExpected;
use App\Assessment\Problems\SameStandardRequired;
use App\Client;
use App\ClientMother;
use App\Clock\Clock;
use App\Clock\ClockStub;
use App\Standard;
use App\StandardMother;
use App\SupervisorMother;
use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class FurtherEvaluationConductorTest extends TestCase
{
    private AssessmentRepository $assessments;
    private Standard $standard;
    private Client $client;
    private Clock $clock;
    private Assessment $newAssessment;

    public function test same standard is required(): void
    {
        $this->given client has a previous assessment in a different standard();
        $this->then a same standard required exception is expected();
        $this->when a further evaluation is conducted();
    }

    public function test evaluations require a grace period(): void
    {
        $this->given client has a previous negative assessment from a week ago();
        $this->then grace period required exception is expected();
        $this->when a further evaluation is conducted();
    }

    public function test new evaluation replaces the current one(): void
    {
        $this->given client has a previous assessment from a year ago();
        $this->when a further evaluation is conducted();
        $this->then the newly obtained assessment replaces the current one();
    }

    private function given client has a previous assessment in a different standard(): void
    {
        $this->client has a previous assessment(
            standard: StandardMother::some(),
            date: $this->clock->now()->sub(new DateInterval('P1Y')),
        );
    }

    private function given client has a previous assessment from a year ago(): void
    {
        $this->client has a previous assessment(
            date: $this->clock->now()->sub(new DateInterval('P1Y')),
        );
    }

    private function given client has a previous negative assessment from a week ago(): void
    {
        $this->client has a previous assessment(
            evaluation: Evaluation::Negative,
            date: $this->clock->now()->sub(new DateInterval('P7D')),
        );
    }

    private function when a further evaluation is conducted(): void
    {
        $sut = new FurtherEvaluationConductor($this->assessments, $this->clock);
        $sut->conduct($this->newAssessment);
    }

    private function then the newly obtained assessment replaces the current one(): void
    {
        $actual = $this->assessments->findForClient($this->client);
        self::assertSame($this->newAssessment, $actual);
    }

    private function then a same standard required exception is expected(): void
    {
        self::expectException(SameStandardRequired::class);
    }

    private function then grace period required exception is expected(): void
    {
        self::expectException(GracePeriodExpected::class);
    }

    private function client has a previous assessment(
        Standard $standard = null,
        Evaluation $evaluation = null,
        DateTimeImmutable $date = null,
    ): void {
        $this->assessments = InMemoryAssessmentRepository::of(
            new Assessment(
                $this->client,
                SupervisorMother::some(),
                $standard ?? $this->standard,
                $date ?? $this->clock->now(),
                $evaluation ?? Evaluation::Negative,
            ),
        );
    }

    protected function setUp(): void
    {
        $this->standard = StandardMother::some();
        $this->client = ClientMother::some();
        $this->clock = ClockStub::of(new DateTimeImmutable());
        $this->newAssessment = new Assessment(
            $this->client,
            SupervisorMother::some(),
            $this->standard,
            $this->clock->now(),
            Evaluation::Positive,
        );
    }
}
