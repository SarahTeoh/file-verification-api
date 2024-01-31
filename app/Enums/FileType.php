<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumValues;

enum FileType: string
{
    use EnumValues;

    case Json = 'application/json';
    // Add more support file type in the future
}
