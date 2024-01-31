<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumValues;

enum HttpResponseCode: int
{
    use EnumValues;

    case Ok = 200;
    case BadRequest = 400;
    case Unauthorized = 401;
    case Forbidden = 403;
    case NotFound = 404;
    case InternalServerError = 500;
    case InvalidServerResponseError = 502;
    case ServiceUnavailableError = 503;
}
