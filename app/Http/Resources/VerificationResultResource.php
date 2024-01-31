<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\DataTransferObjects\VerificationResultDto
 */
class VerificationResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'issuer' => $this->issuer,
            'result' => $this->result,
        ];
    }
}
