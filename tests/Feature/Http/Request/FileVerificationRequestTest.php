<?php

declare(strict_types=1);

use App\Http\Requests\FileVerificationRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

it('validates that a file is required', function () {
    $request = new FileVerificationRequest();

    $validator = Validator::make([], $request->rules());

    $this->assertTrue($validator->fails());
    $this->assertContains('file', $validator->errors()->keys());
});

it('validates that the file is of a valid type', function () {
    $file = UploadedFile::fake()->create('document.txt');

    $request = new FileVerificationRequest();

    $validator = Validator::make(['file' => $file], $request->rules());

    $this->assertTrue($validator->fails());
    $this->assertContains('file', $validator->errors()->keys());
});

it('validates that the file is not larger than 2MB', function () {
    $file = UploadedFile::fake()->create('document.json', 3000);

    $request = new FileVerificationRequest();

    $validator = Validator::make(['file' => $file], $request->rules());

    $this->assertTrue($validator->fails());
    $this->assertContains('file', $validator->errors()->keys());
});

it('passes validation when the file is uploaded, is of a valid type and is not larger than 2MB', function () {
    $file = UploadedFile::fake()->create('document.json', 1000);

    $request = new FileVerificationRequest();

    $validator = Validator::make(['file' => $file], $request->rules());

    $this->assertFalse($validator->fails());
});
