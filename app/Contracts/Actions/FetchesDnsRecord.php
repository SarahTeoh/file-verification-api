<?php

declare(strict_types=1);

namespace App\Contracts\Actions;

interface FetchesDnsRecord
{
    public function __invoke(string $domain): array;
}
