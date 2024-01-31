<?php

declare(strict_types=1);

use App\Actions\ProcessJsonFile;
use App\Exceptions\FileProcessException;

it('processes a valid JSON string correctly', function () {
    $json = json_encode([
        'data' => [
            'id' => '1',
            'name' => 'Test',
            'recipient' => [
                'name' => 'Recipient',
                'email' => 'recipient@example.com',
            ],
            'issuer' => [
                'name' => 'Issuer',
                'identityProof' => [
                    'type' => 'Type',
                    'key' => 'Key',
                    'location' => 'Location',
                ],
            ],
            'issued' => '2022-01-01',
        ],
        'signature' => [
            'type' => 'Type',
            'targetHash' => 'Hash',
        ],
    ]);

    $action = new ProcessJsonFile();

    $result = $action->__invoke($json);

    expect($result)->toBe([
        'data' => [
            'id' => '1',
            'name' => 'Test',
            'recipient' => [
                'name' => 'Recipient',
                'email' => 'recipient@example.com',
            ],
            'issuer' => [
                'name' => 'Issuer',
                'identityProof' => [
                    'type' => 'Type',
                    'key' => 'Key',
                    'location' => 'Location',
                ],
            ],
            'issued' => '2022-01-01',
        ],
        'signature' => [
            'type' => 'Type',
            'targetHash' => 'Hash',
        ],
    ]);
});

it('throws a JsonException when given invalid JSON', function () {
    $json = '{"key": "value"';

    $processJsonFile = new ProcessJsonFile();

    expect(fn () => $processJsonFile->__invoke($json))->toThrow(
        FileProcessException::class,
        (FileProcessException::decodeJsonFailed())->getMessage()
    );
});

it('handles missing fields in the JSON string', function () {
    $json = json_encode([
        'data' => [
            'id' => '1',
            'name' => 'Test',
        ],
        'signature' => [
            'type' => 'Type',
        ],
    ]);

    $action = new ProcessJsonFile();

    $result = $action->__invoke($json);

    expect($result)->toBe([
        'data' => [
            'id' => '1',
            'name' => 'Test',
            'recipient' => [
                'name' => '',
                'email' => '',
            ],
            'issuer' => [
                'name' => '',
                'identityProof' => [
                    'type' => '',
                    'key' => '',
                    'location' => '',
                ],
            ],
            'issued' => '',
        ],
        'signature' => [
            'type' => 'Type',
            'targetHash' => '',
        ],
    ]);
});
