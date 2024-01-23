<?php

namespace App\Rules;

use App\Enums\VerificationResult;
use App\Rules\VerificationRule;

class ValidRecipientRule extends VerificationRule
{
    public function __construct()
    {
        $this->errorCode = VerificationResult::InvalidRecipient->value;
    }

    public function verify(array $jsonData): bool
    {
        return isset($data['recipient']['name']) && isset($data['recipient']['email']);
    }
}