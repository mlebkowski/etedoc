<?php
declare(strict_types=1);

namespace App\Assessment;

use App\Assessment\Requirements\AuthorityRequirement;
use App\Assessment\Requirements\ContractRequirement;
use App\Assessment\Requirements\Problems\AuthorityRequirementUnmet;
use App\Assessment\Requirements\Problems\ValidContractMissing;
use App\Authority\Authority;
use App\Authority\AuthorityRepository;
use App\Authority\AuthorityRepositoryMother;
use App\Authority\InMemoryAuthorityRepository;
use App\Client;
use App\ClientMother;
use App\Clock\Clock;
use App\Clock\ClockStub;
use App\Contract\Contract;
use App\Contract\ContractRepository;
use App\Contract\ContractRepositoryMother;
use App\Contract\ContractStatus;
use App\Contract\InMemoryContractRepository;
use App\Standard;
use App\StandardMother;
use App\Supervisor;
use App\SupervisorMother;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class AssessmentFactoryTest extends TestCase
{
    private ContractRepository $contracts;
    private AuthorityRepository $authorities;
    private Standard $standard;
    private Client $client;
    private Supervisor $supervisor;
    private Clock $clock;

    public function test passes when active contract is present(): void
    {
        $this->given an active contract is present();
        $this->given supervisor has a valid authority for standard();
        $this->when an assessment is carried out();
        $this->requirement passes();
    }

    public function test fails when inactive contract is present(): void
    {
        $this->given an inactive contract is present();
        $this->given supervisor has a valid authority for standard();
        $this->then an valid contract missing is expected();
        $this->when an assessment is carried out();
    }

    public function test fails when no contract is present(): void
    {
        $this->given no contract is present();
        $this->given supervisor has a valid authority for standard();
        $this->then an valid contract missing is expected();
        $this->when an assessment is carried out();
    }

    public function test ensure satisfied when supervisor has authority for the date(): void
    {
        $this->given an active contract is present();
        $this->given supervisor has a valid authority for standard();
        $this->when an assessment is carried out();
        $this->requirement passes();
    }

    public function test ensure not satisfied when supervisor has an expired authority(): void
    {
        $this->given an active contract is present();
        $this->given supervisor has an expired authority for the standard();
        $this->then an authority requirement unmet is expected();
        $this->when an assessment is carried out();
    }

    public function test ensure not satisfied when supervisor does not have an authority for standard(): void
    {
        $this->given an active contract is present();
        $this->given supervisor has an authority for a different standard();
        $this->then an authority requirement unmet is expected();
        $this->when an assessment is carried out();
    }

    private function given an active contract is present(): void
    {
        $this->contracts = InMemoryContractRepository::of(
            new Contract(
                $this->client,
                $this->supervisor,
                ContractStatus::Active,
            ),
        );
    }

    private function given an inactive contract is present(): void
    {
        $this->contracts = InMemoryContractRepository::of(
            new Contract(
                $this->client,
                $this->supervisor,
                ContractStatus::Inactive,
            ),
        );
    }

    private function given no contract is present(): void
    {
        $this->contracts = InMemoryContractRepository::of();
    }

    private function given supervisor has a valid authority for standard(): void
    {
        $this->authorities = InMemoryAuthorityRepository::of(
            new Authority(
                $this->supervisor,
                $this->standard,
                $this->clock->now()->modify('-1 day'),
                $this->clock->now()->modify('+1 day'),
            ),
        );
    }

    private function given supervisor has an expired authority for the standard(): void
    {
        $this->authorities = InMemoryAuthorityRepository::of(
            new Authority(
                $this->supervisor,
                $this->standard,
                $this->clock->now()->modify('-2 days'),
                $this->clock->now()->modify('-1 days'),
            ),
        );
    }

    private function given supervisor has an authority for a different standard(): void
    {
        $this->authorities = InMemoryAuthorityRepository::of(
            new Authority(
                $this->supervisor,
                StandardMother::some(),
                $this->clock->now()->modify('-1 day'),
                $this->clock->now()->modify('+1 day'),
            ),
        );
    }

    private function when an assessment is carried out(): void
    {
        $sut = new AssessmentFactory(
            new AuthorityRequirement($this->authorities),
            new ContractRequirement($this->contracts),
            $this->clock,
        );

        $sut->make($this->client, $this->supervisor, $this->standard, Evaluation::Positive);
    }

    private function requirement passes(): void
    {
        self::assertTrue(true); // noop
    }

    private function then an authority requirement unmet is expected(): void
    {
        self::expectException(AuthorityRequirementUnmet::class);
    }

    private function then an valid contract missing is expected(): void
    {
        self::expectException(ValidContractMissing::class);
    }

    protected function setUp(): void
    {
        $this->authorities = AuthorityRepositoryMother::some();
        $this->supervisor = SupervisorMother::some();
        $this->standard = StandardMother::some();
        $this->client = ClientMother::some();
        $this->contracts = ContractRepositoryMother::some();
        $this->clock = ClockStub::of(new DateTimeImmutable());
    }
}
