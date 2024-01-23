<?php

namespace App\Interfaces;

use App\DataTransferObjects\VerificationHistory as VerificationHistoryDTO;
use App\Models\VerificationHistory;

interface VerificationHistoryRepositoryInterface
{
    public function create(VerificationHistoryDTO $VerificationHistoryDTO): VerificationHistory;
}