<?php

namespace App\DataTransferObjects;

class VerificationHistory
{
    public function __construct(
        public int $userId,
        public string $fileType,
        public string $result,
        public string $timestamp
    ) {}
}