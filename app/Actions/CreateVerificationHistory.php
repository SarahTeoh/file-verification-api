<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\Actions\CreatesVerificationHistory;
use App\DataTransferObjects\VerificationHistoryDto;
use App\Models\VerificationHistory;

class CreateVerificationHistory implements CreatesVerificationHistory
{
    public function __invoke(VerificationHistoryDto $verificationHistoryDto): VerificationHistory
    {
        return VerificationHistory::create([
            'user_id' => $verificationHistoryDto->userId,
            'file_type' => $verificationHistoryDto->fileType,
            'result' => $verificationHistoryDto->result,
        ]);
    }
}
