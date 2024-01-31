<?php

declare(strict_types=1);

namespace App\Contracts\Actions;

use App\DataTransferObjects\VerificationHistoryDto;
use App\Models\VerificationHistory;

interface CreatesVerificationHistory
{
    public function __invoke(VerificationHistoryDto $verificationHistoryDto): VerificationHistory;
}
