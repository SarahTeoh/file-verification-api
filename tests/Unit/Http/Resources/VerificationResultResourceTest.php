<?php

declare(strict_types=1);

use App\DataTransferObjects\VerificationResultDto;
use App\Http\Resources\VerificationResultResource;
use Illuminate\Http\Request;

it('wrap resource with data correctly', function () {
    $verificationResultDTO = new VerificationResultDto(
        'Test issuer',
        'Test result'
    );

    $resource = new VerificationResultResource($verificationResultDTO);

    $expectedData = [
        'issuer' => 'Test issuer',
        'result' => 'Test result',
    ];

    $request = Request::create('/test', 'GET');
    expect($resource->toArray($request))->toEqual($expectedData);
});
