<?php

declare(strict_types=1);

use App\Actions\HandleFileVerification;
use App\Contracts\Actions\CreatesVerificationHistory;
use App\Contracts\Actions\ProcessesFile;
use App\Contracts\Actions\VerifiesFile;
use App\DataTransferObjects\VerificationHistoryDto;
use App\DataTransferObjects\VerificationResultDto;
use App\Enums\FileType;
use App\Enums\VerificationResult;

beforeEach(function () {
    $this->fileContentString = 'file content';
    $this->userId = 1;
    $this->mockProcessFile = Mockery::mock(ProcessesFile::class);
    $this->mockVerifyFile = Mockery::mock(VerifiesFile::class);
    $this->mockCreateVerificationHistory = Mockery::mock(CreatesVerificationHistory::class);
});

it('handles file verification', function () {
    $fileContentArray = ['data' => ['issuer' => ['name' => 'Issuer Name']]];
    $verificationHistoryDto = new VerificationHistoryDto($this->userId, FileType::Json->value, VerificationResult::Verified->value);

    $this->mockProcessFile->shouldReceive('__invoke')
        ->once()
        ->with($this->fileContentString)
        ->andReturn($fileContentArray);

    $this->mockVerifyFile->shouldReceive('__invoke')
        ->once()
        ->with($fileContentArray, $this->userId)
        ->andReturn($verificationHistoryDto);

    $this->mockCreateVerificationHistory->shouldReceive('__invoke')
        ->once()
        ->with($verificationHistoryDto);

    $action = new HandleFileVerification($this->mockProcessFile, $this->mockVerifyFile, $this->mockCreateVerificationHistory);
    $result = $action->__invoke($this->fileContentString, $this->userId);

    expect($result)->toBeInstanceOf(VerificationResultDto::class);
    expect($result->issuer)->toBe($fileContentArray['data']['issuer']['name']);
    expect($result->result)->toBe($verificationHistoryDto->result);
});

it('handles file verification with missing issuer name', function () {
    $fileContentArray = ['data' => ['issuer' => []]];
    $verificationHistoryDto = new VerificationHistoryDto($this->userId, FileType::Json->value, VerificationResult::InvalidIssuer->value);

    $this->mockProcessFile->shouldReceive('__invoke')
        ->once()
        ->with($this->fileContentString)
        ->andReturn($fileContentArray);

    $this->mockVerifyFile->shouldReceive('__invoke')
        ->once()
        ->with($fileContentArray, $this->userId)
        ->andReturn($verificationHistoryDto);

    $this->mockCreateVerificationHistory->shouldReceive('__invoke')
        ->once()
        ->with($verificationHistoryDto);

    $action = new HandleFileVerification($this->mockProcessFile, $this->mockVerifyFile, $this->mockCreateVerificationHistory);
    $result = $action->__invoke($this->fileContentString, $this->userId);

    expect($result)->toBeInstanceOf(VerificationResultDto::class);
    expect($result->issuer)->toBe('');
    expect($result->result)->toBe($verificationHistoryDto->result);
});
