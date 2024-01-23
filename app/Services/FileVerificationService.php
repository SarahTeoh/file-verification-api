<?php 

namespace App\Services;

use App\DataTransferObjects\VerificationHistory;
use Illuminate\Http\UploadedFile;

abstract class FileVerificationService
{
    abstract public function verify(UploadedFile $file, int $userId): VerificationHistory;
}