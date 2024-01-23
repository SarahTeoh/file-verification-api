<?php 

namespace App\Services;

use App\DataTransferObjects\VerificationHistory as VerificationHistoryDTO;
use App\Enums\FileType;
use App\Enums\VerificationResult;
use App\Repositories\VerificationHistoryRepository;
use Carbon\CarbonImmutable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class JsonFileVerificationService extends FileVerificationService
{
    public function __construct(
        private VerificationHistoryRepository $verificationHistoryRepository,
        private array $rules
    ) {}

    public function verify(UploadedFile $jsonFile, int $userId): VerificationHistoryDTO
    {
        $jsonArray = $this->readJsonFile($jsonFile);
        $result = VerificationResult::Verified->value;
        foreach ($this->rules as $rule) {
            if (!$rule->verify($jsonArray)) {
                $result = $rule->getErrorCode();
                break;
            }
        }
        $verificationHistoryDTO = new VerificationHistoryDTO(
            $userId,
            FileType::Json->value,
            $result,
            CarbonImmutable::now()
        );

        $this->verificationHistoryRepository->create($verificationHistoryDTO);
        return $verificationHistoryDTO;
    }

    private function readJsonFile(UploadedFile $jsonFile): array
    {
        return json_decode(File::get($jsonFile), true);
    }
}