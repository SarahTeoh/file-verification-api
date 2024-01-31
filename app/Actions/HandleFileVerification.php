<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\Actions\CreatesVerificationHistory;
use App\Contracts\Actions\HandlesFileVerification;
use App\Contracts\Actions\ProcessesFile;
use App\Contracts\Actions\VerifiesFile;
use App\DataTransferObjects\VerificationResultDto;

class HandleFileVerification implements HandlesFileVerification
{
    public function __construct(
        private ProcessesFile $processFile,
        private VerifiesFile $verifyFile,
        private CreatesVerificationHistory $createVerificationHistory
    ) {

    }

    public function __invoke(
        string $fileContentString,
        int $userId
    ): VerificationResultDto {
        $fileContentArray = ($this->processFile)($fileContentString);
        $verificationHistoryDto = ($this->verifyFile)($fileContentArray, $userId);
        ($this->createVerificationHistory)($verificationHistoryDto);

        return new VerificationResultDto(
            $fileContentArray['data']['issuer']['name'] ?? '',
            $verificationHistoryDto->result
        );
    }
}
