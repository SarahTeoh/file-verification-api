<?php

declare(strict_types=1);

namespace App\Contracts\Actions;

use App\DataTransferObjects\VerificationResultDto;

interface HandlesFileVerification
{
    public function __invoke(string $fileContent, int $userId): VerificationResultDto;
}
