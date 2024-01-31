<?php

declare(strict_types=1);

use App\Actions\CreateVerificationHistory;
use App\DataTransferObjects\VerificationHistoryDto;
use App\Enums\FileType;
use App\Enums\VerificationResult;
use App\Models\User;
use App\Models\VerificationHistory;

it('creates a verification history', function () {
    $userId = User::factory()->create()->id;
    $verificationHistoryDto = new VerificationHistoryDto(
        userId: $userId,
        fileType: FileType::Json->value,
        result: VerificationResult::Verified->value
    );

    $action = new CreateVerificationHistory();
    $verificationHistory = $action->__invoke($verificationHistoryDto);

    expect($verificationHistory)->toBeInstanceOf(VerificationHistory::class);

    // Query the result from the database and compare
    $storedVerificationHistory = VerificationHistory::latest()->first();

    expect($storedVerificationHistory)->toBeInstanceOf(VerificationHistory::class);
    expect($storedVerificationHistory->user_id)->toBe($verificationHistoryDto->userId)
        ->and($storedVerificationHistory->file_type->value)->toBe($verificationHistoryDto->fileType)
        ->and($storedVerificationHistory->result->value)->toBe($verificationHistoryDto->result);
});
