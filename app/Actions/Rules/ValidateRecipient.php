<?php

declare(strict_types=1);

namespace App\Actions\Rules;

use App\Contracts\Actions\Rules\ValidatesAgainstRule;
use App\DataTransferObjects\ValidationResultDto;
use App\Enums\VerificationResult;

class ValidateRecipient implements ValidatesAgainstRule
{
    public function __construct(private string $errorCode = VerificationResult::InvalidRecipient->value
    ) {
    }

    public function __invoke(array $data): ValidationResultDto
    {
        $isValid = $this->hasValidRecipient($data);

        return new ValidationResultDto($isValid, $isValid ? null : $this->errorCode);
    }

    private function hasValidRecipient(array $data): bool
    {
        return isset($data['data']['recipient']['name']) && isset($data['data']['recipient']['email']);
    }
}
