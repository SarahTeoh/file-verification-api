<?php

declare(strict_types=1);

namespace App\Traits;

trait EnumValues
{
    /**
     * @return array<int, int|string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
