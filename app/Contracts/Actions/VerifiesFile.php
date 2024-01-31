<?php

declare(strict_types=1);

namespace App\Contracts\Actions;

use App\DataTransferObjects\VerificationHistoryDto;

interface VerifiesFile
{
    public function __invoke(array $fileContent, int $userId): VerificationHistoryDto;
}
