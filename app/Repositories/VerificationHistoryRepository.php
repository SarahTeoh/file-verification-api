<?php

namespace App\Repositories;

use App\DataTransferObjects\VerificationHistory as VerificationHistoryDTO;
use App\Interfaces\VerificationHistoryRepositoryInterface;
use App\Models\VerificationHistory;

class VerificationHistoryRepository implements VerificationHistoryRepositoryInterface
{
    public function create(VerificationHistoryDTO $verificationHistoryDTO): VerificationHistory
    {
        return VerificationHistory::create([
            'user_id' => $verificationHistoryDTO->userId,
            'file_type' => $verificationHistoryDTO->fileType,
            'result' => $verificationHistoryDTO->result,
            'timestamp' => $verificationHistoryDTO->timestamp,
        ]);
    }
}