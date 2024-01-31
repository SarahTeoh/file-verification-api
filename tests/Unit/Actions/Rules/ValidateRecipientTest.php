<?php

declare(strict_types=1);

use App\Actions\Rules\ValidateRecipient;
use App\DataTransferObjects\ValidationResultDto;
use App\Enums\VerificationResult;

beforeEach(function () {
    $this->data = [
        'data' => [
            'recipient' => [
                'name' => 'example',
                'email' => 'example@example.com',
            ],
        ],
    ];
    $this->errorCode = VerificationResult::InvalidRecipient->value;
});

it('validates recipient successfully', function () {
    $validateRecipient = new ValidateRecipient();

    $result = $validateRecipient($this->data);

    expect($result)->toBeInstanceOf(ValidationResultDto::class);
    expect($result->isValid)->toBeTrue();
});

it('returns invalid if recipient name is missing', function () {
    unset($this->data['data']['recipient']['name']);

    $validateRecipient = new ValidateRecipient();

    $result = $validateRecipient($this->data);

    expect($result)->toBeInstanceOf(ValidationResultDto::class);
    expect($result->isValid)->toBeFalse();
    expect($result->errorCode)->toBe($this->errorCode);
});

it('returns invalid if recipient email is missing', function () {
    unset($this->data['data']['recipient']['email']);

    $validateRecipient = new ValidateRecipient();

    $result = $validateRecipient($this->data);

    expect($result)->toBeInstanceOf(ValidationResultDto::class);
    expect($result->isValid)->toBeFalse();
    expect($result->errorCode)->toBe($this->errorCode);
});

it('returns invalid if recipient is missing', function () {
    unset($this->data['data']['recipient']);

    $validateRecipient = new ValidateRecipient();

    $result = $validateRecipient($this->data);

    expect($result)->toBeInstanceOf(ValidationResultDto::class);
    expect($result->isValid)->toBeFalse();
    expect($result->errorCode)->toBe($this->errorCode);
});
