<?php

declare(strict_types=1);

namespace App\Enums;

enum FileType: string
{
    case Json = 'application/json';
    // Add more support file type in the future
}
