<?php

declare(strict_types=1);

use App\Actions\FetchDnsRecord;
use App\Actions\Rules\ValidateIssuer;
use App\DataTransferObjects\ValidationResultDto;
use App\Enums\VerificationResult;

beforeEach(function () {
    $this->fetchDnsRecord = Mockery::mock(FetchDnsRecord::class);
    $this->errorCode = VerificationResult::InvalidIssuer->value;
    $this->data = [
        'data' => [
            'issuer' => [
                'name' => 'example',
                'identityProof' => [
                    'key' => 'key',
                    'location' => 'example.com',
                ],
            ],
        ],
    ];

});

it('validates issuer successfully', function () {
    $this->fetchDnsRecord->shouldReceive('__invoke')
        ->andReturn([
            [
                'type' => 16,
                'data' => 'key',
            ],
        ]);

    $validateIssuer = new ValidateIssuer($this->fetchDnsRecord);

    $result = $validateIssuer($this->data);

    expect($result)->toBeInstanceOf(ValidationResultDto::class);
    expect($result->isValid)->toBeTrue();
});

it('returns invalid if issuer is not valid', function () {
    $this->fetchDnsRecord->shouldReceive('__invoke')
        ->andReturn([
            [
                'type' => 16,
                'data' => 'some other string', //issuer identityProof key is not in DNS TXT record
            ],
        ]);

    $validateIssuer = new ValidateIssuer($this->fetchDnsRecord);

    $result = $validateIssuer($this->data);

    expect($result)->toBeInstanceOf(ValidationResultDto::class);
    expect($result->isValid)->toBeFalse();
    expect($result->errorCode)->toBe($this->errorCode);
});

it('returns invalid if issuer name is missing', function () {
    unset($this->data['data']['issuer']['name']);

    $validateIssuer = new ValidateIssuer($this->fetchDnsRecord);

    $result = $validateIssuer($this->data);

    expect($result)->toBeInstanceOf(ValidationResultDto::class);
    expect($result->isValid)->toBeFalse();
    expect($result->errorCode)->toBe($this->errorCode);
});

it('returns invalid if identityProof is missing', function () {
    unset($this->data['data']['issuer']['identityProof']);

    $validateIssuer = new ValidateIssuer($this->fetchDnsRecord);

    $result = $validateIssuer($this->data);

    expect($result)->toBeInstanceOf(ValidationResultDto::class);
    expect($result->isValid)->toBeFalse();
    expect($result->errorCode)->toBe($this->errorCode);
});

it('returns invalid if identityProof key is an empty string', function () {
    $this->data['data']['issuer']['identityProof']['key'] = '';

    $validateIssuer = new ValidateIssuer($this->fetchDnsRecord);

    $result = $validateIssuer($this->data);

    expect($result)->toBeInstanceOf(ValidationResultDto::class);
    expect($result->isValid)->toBeFalse();
    expect($result->errorCode)->toBe($this->errorCode);
});

it('returns invalid if identityProof location is an empty string', function () {
    $this->data['data']['issuer']['identityProof']['location'] = '';

    $validateIssuer = new ValidateIssuer($this->fetchDnsRecord);

    $result = $validateIssuer($this->data);

    expect($result)->toBeInstanceOf(ValidationResultDto::class);
    expect($result->isValid)->toBeFalse();
    expect($result->errorCode)->toBe($this->errorCode);
});
