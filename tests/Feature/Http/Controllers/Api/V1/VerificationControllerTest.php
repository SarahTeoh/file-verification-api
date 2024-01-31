<?php

declare(strict_types=1);

use App\Contracts\Actions\HandlesFileVerification;
use App\DataTransferObjects\VerificationResultDto;
use App\Enums\VerificationResult;
use App\Http\Resources\VerificationResultResource;
use App\Models\User;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('verifies a file', function () {
    $file = UploadedFile::fake()->createWithContent('document.json', json_encode(['data' => ['issuer' => ['name' => 'Issuer Name', 'identityProof' => ['key' => 'key', 'location' => 'example.com']]]]));
    $this->userId = 1;
    $verificationResultDto = new VerificationResultDto(
        issuer: 'Issuer Name',
        result: VerificationResult::Verified->value
    );

    $mockHandleFileVerification = Mockery::mock(HandlesFileVerification::class);
    $mockHandleFileVerification->shouldReceive('__invoke')
        ->once()
        ->with($file->getContent(), $this->userId)
        ->andReturn($verificationResultDto);

    // Bind the mock to the service container
    $this->app->instance(HandlesFileVerification::class, $mockHandleFileVerification);

    $response = $this->actingAs($this->user)
        ->post('/api/v1/verify', ['file' => $file]);
    $response->assertStatus(200);
    $response->assertJsonFragment((new VerificationResultResource($verificationResultDto))->toArray(request()));
});

it('returns OK even if the file not verified', function () {
    // issuer.key missing
    $file = UploadedFile::fake()->createWithContent('document.json', json_encode(['data' => ['issuer' => ['name' => 'Issuer Name']]]));
    $this->userId = 1;
    $verificationResultDto = new VerificationResultDto(
        issuer: 'Issuer Name',
        result: VerificationResult::InvalidIssuer->value
    );

    $mockHandleFileVerification = Mockery::mock(HandlesFileVerification::class);
    $mockHandleFileVerification->shouldReceive('__invoke')
        ->once()
        ->with($file->getContent(), $this->userId)
        ->andReturn($verificationResultDto);

    // Bind the mock to the service container
    $this->app->instance(HandlesFileVerification::class, $mockHandleFileVerification);

    $response = $this->actingAs($this->user)
        ->post('/api/v1/verify', ['file' => $file]);
    $response->assertStatus(200);
    $response->assertJsonFragment((new VerificationResultResource($verificationResultDto))->toArray(request()));
});
