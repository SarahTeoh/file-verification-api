<?php

namespace App\Factories;

use App\Services\FileVerificationService;
use App\Services\JsonFileVerificationService;
use App\Enums\FileType;
use App\Repositories\VerificationHistoryRepository;
use App\Rules\VerificationRule;
use Exception;
use Illuminate\Http\UploadedFile;

class FileVerificationServiceFactory
{
    public function __construct(
        private VerificationHistoryRepository $verificationHistoryRepository,
        private array $rules
    ) {}

    public function create(UploadedFile $file): FileVerificationService
    {
        switch ($file->getMimeType()) {
            case FileType::Json:
                return new JsonFileVerificationService($this->verificationHistoryRepository, $this->rules);
                // Add more cases for future file types
            default:
                // TODO: create new exception?
                throw new Exception();
        }
    }
}