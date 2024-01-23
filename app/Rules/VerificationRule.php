<?php

namespace App\Rules;

use App\DataTransferObjects\VerificationHistory;
use Illuminate\Support\Facades\File;

abstract class VerificationRule
{
    protected string $errorCode;

    abstract public function verify(array $data): bool;

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
