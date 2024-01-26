<?php

declare(strict_types=1);

use App\Enums\VerificationResult;

it('returns the correct values', function () {
    $expectedValues = ['verified', 'invalid_recipient', 'invalid_issuer', 'invalid_signature'];

    $actualValues = array_map(fn ($case) => $case->value, VerificationResult::cases());

    expect($actualValues)->toBe($expectedValues);
});

it('returns the correct value for each case', function () {
    expect(VerificationResult::Verified->value)->toBe('verified');
    expect(VerificationResult::InvalidRecipient->value)->toBe('invalid_recipient');
    expect(VerificationResult::InvalidIssuer->value)->toBe('invalid_issuer');
    expect(VerificationResult::InvalidSignature->value)->toBe('invalid_signature');
});