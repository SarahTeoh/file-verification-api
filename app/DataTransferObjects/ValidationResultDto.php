<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

class ValidationResultDto
{
    public function __construct(
        public bool $isValid,
        public ?string $errorCode = null
    ) {
    }
}
