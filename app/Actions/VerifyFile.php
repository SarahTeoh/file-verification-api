<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\Actions\VerifiesFile;
use App\DataTransferObjects\VerificationHistoryDto;
use App\Enums\FileType;
use App\Enums\VerificationResult;

class VerifyFile implements VerifiesFile
{
    /**
     * @param  array<\App\Contracts\Actions\Rules\ValidatesAgainstRule>  $rulesToValidate
     */
    public function __construct(private array $rulesToValidate)
    {
    }

    public function __invoke(array $fileContent, int $userId): VerificationHistoryDto
    {
        $result = VerificationResult::Verified->value;

        foreach ($this->rulesToValidate as $rule) {
            $validationResult = ($rule)($fileContent);
            if (! $validationResult->isValid && ! is_null($validationResult->errorCode)) {
                $result = $validationResult->errorCode;
                break;
            }
        }

        $verificationHistoryDto = new VerificationHistoryDTO(
            $userId,
            FileType::Json->value,
            $result
        );

        return new VerificationHistoryDto(
            $verificationHistoryDto->userId,
            FileType::Json->value,
            $verificationHistoryDto->result,
        );
    }
}
