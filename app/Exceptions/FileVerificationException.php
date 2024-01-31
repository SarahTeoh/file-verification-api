<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\HttpResponseCode;

class FileVerificationException extends CustomException
{
    public static function hashSignatureFailed(): self
    {
        return new self('Failed to hash signature', HttpResponseCode::InternalServerError->value);
    }
}
