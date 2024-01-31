<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\HttpResponseCode;

class FileProcessException extends CustomException
{
    public static function decodeJsonFailed(): self
    {
        return new self('Failed to decode Json', HttpResponseCode::InternalServerError->value);
    }

    public static function encodeJsonFailed(): self
    {
        return new self('Failed to encode Json', HttpResponseCode::InternalServerError->value);
    }
}
