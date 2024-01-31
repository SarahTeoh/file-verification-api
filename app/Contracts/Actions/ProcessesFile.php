<?php

declare(strict_types=1);

namespace App\Contracts\Actions;

interface ProcessesFile
{
    public function __invoke(string $fileContent): array;
}
