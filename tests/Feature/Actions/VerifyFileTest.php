<?php

declare(strict_types=1);

use App\Actions\VerifyFile;
use App\Contracts\Actions\Rules\ValidatesAgainstRule;
use App\DataTransferObjects\ValidationResultDto;
use App\DataTransferObjects\VerificationHistoryDto;
use App\Enums\VerificationResult;

beforeEach(function () {
    $this->userId = 1;
});

it('verifies file with all rules passing', function () {
    $mockRule1 = Mockery::mock(ValidatesAgainstRule::class);
    $mockRule1->shouldReceive('__invoke')
        ->once()
        ->andReturn(new ValidationResultDto(true));

    $mockRule2 = Mockery::mock(ValidatesAgainstRule::class);
    $mockRule2->shouldReceive('__invoke')
        ->once()
        ->andReturn(new ValidationResultDto(true));

    $rulesToValidate = [$mockRule1, $mockRule2];

    $action = new VerifyFile($rulesToValidate);
    $result = $action->__invoke([], $this->userId);

    expect($result)->toBeInstanceOf(VerificationHistoryDto::class);
    expect($result->result)->toBe(VerificationResult::Verified->value);
});

it('verifies file with some rules failing', function () {
    $mockRule1 = Mockery::mock(ValidatesAgainstRule::class);
    $mockRule1->shouldReceive('__invoke')
        ->once()
        ->andReturn(new ValidationResultDto(true));

    // mock rule that will not pass
    $mockRule2 = Mockery::mock(ValidatesAgainstRule::class);
    $mockRule2->shouldReceive('__invoke')
        ->once()
        ->andReturn(new ValidationResultDto(false, VerificationResult::InvalidSignature->value));

    $rulesToValidate = [$mockRule1, $mockRule2];

    $action = new VerifyFile($rulesToValidate);
    $result = $action->__invoke([], $this->userId);

    expect($result)->toBeInstanceOf(VerificationHistoryDto::class);
    expect($result->result)->toBe(VerificationResult::InvalidSignature->value);
});

it('verifies file with no rules', function () {
    $rulesToValidate = [];

    $action = new VerifyFile($rulesToValidate);
    $result = $action->__invoke([], $this->userId);

    expect($result)->toBeInstanceOf(VerificationHistoryDto::class);
    expect($result->result)->toBe(VerificationResult::Verified->value);
});
