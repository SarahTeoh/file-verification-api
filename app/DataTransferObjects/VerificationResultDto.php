<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

class VerificationResultDto
{
    public function __construct(
        public string $issuer,
        public string $result,
    ) {
    }
}
