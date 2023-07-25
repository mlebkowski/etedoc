<?php
declare(strict_types=1);

namespace App\Assessment;

use App\Client;

interface AssessmentRepository
{
    public function findForClient(Client $client): ?Assessment;

    public function replace(Assessment $previous, Assessment $current): void;

    public function save(Assessment $assessment): void;
}
