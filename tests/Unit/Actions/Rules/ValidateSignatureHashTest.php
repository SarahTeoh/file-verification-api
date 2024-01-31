<?php

declare(strict_types=1);

use App\Actions\Rules\ValidateSignatureHash;
use App\DataTransferObjects\ValidationResultDto;
use App\Enums\VerificationResult;
use App\Exceptions\FileVerificationException;
use Illuminate\Support\Arr;

function generateTargetHash($data)
{
    $flattenProperties = Arr::dot($data);
    $hashedProperties = array_map(function ($key, $value) {
        return hash('sha256', json_encode([$key => $value]));
    }, array_keys($flattenProperties), $flattenProperties);
    sort($hashedProperties, SORT_STRING);

    return hash('sha256', json_encode($hashedProperties));
}

it('validates a correct signature', function () {
    $action = new ValidateSignatureHash();

    // Define the data
    $data = [
        'id' => '1',
        'name' => 'Test',
    ];

    // Define the data
    $data = [
        'id' => '1',
        'name' => 'Test',
    ];

    // Generate the correct targetHash
    $correctTargetHash = generateTargetHash($data);

    // Define the inputData
    $inputData = [
        'data' => $data,
        'signature' => [
            'targetHash' => $correctTargetHash,
        ],
    ];

    $result = $action->__invoke($inputData);

    expect($result)->toBeInstanceOf(ValidationResultDto::class);
    expect($result->isValid)->toBeTrue();
});

it('invalidates an incorrect signature', function ($data) {
    $action = new ValidateSignatureHash();

    $result = $action->__invoke($data);

    expect($result)->toBeInstanceOf(ValidationResultDto::class);
    expect($result->isValid)->toBeFalse();
    expect($result->errorCode)->toBe(VerificationResult::InvalidSignature->value);
})->with([
    [
        'inputData' => [
            'data' => [
                'id' => '1',
                'name' => 'Test',
            ],
            'signature' => [
                'targetHash' => 'incorrect_hash',
            ],
        ],
    ],
]);

it('invalidates a missing signature', function ($data) {
    $action = new ValidateSignatureHash();

    $result = $action->__invoke($data);

    expect($result)->toBeInstanceOf(ValidationResultDto::class);
    expect($result->isValid)->toBeFalse();
    expect($result->errorCode)->toBe(VerificationResult::InvalidSignature->value);
})->with([
    [
        'inputData' => [
            'data' => [
                'id' => '1',
                'name' => 'Test',
            ],
        ],
    ],
]);

it('throws a FileVerificationException when hashing fails', function ($data) {
    $action = new ValidateSignatureHash();

    expect(function () use ($action, $data) {
        $action->__invoke($data);
    })->toThrow(FileVerificationException::class);
})->with([
    [
        'inputData' => [
            'data' => null, // invalid data that will cause hashing to fail
            'signature' => [
                'targetHash' => 'incorrect_hash',
            ],
        ],
    ],
]);
