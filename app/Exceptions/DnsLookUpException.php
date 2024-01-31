<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\HttpResponseCode;

class DnsLookUpException extends CustomException
{
    public static function dnsLookUpFailed(): self
    {
        return new self('Failed to lookup DNS', HttpResponseCode::ServiceUnavailableError->value);
    }

    public static function invalidDnsResponse(): self
    {
        return new self('Invalid DNS response', HttpResponseCode::InvalidServerResponseError->value);
    }
}
