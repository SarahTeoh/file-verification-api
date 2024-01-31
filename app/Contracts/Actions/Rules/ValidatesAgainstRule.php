<?php

declare(strict_types=1);

namespace App\Contracts\Actions\Rules;

use App\DataTransferObjects\ValidationResultDto;

interface ValidatesAgainstRule
{
    public function __invoke(array $fileContent): ValidationResultDto;
}
