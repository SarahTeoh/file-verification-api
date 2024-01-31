<?php

declare(strict_types=1);

namespace App\Actions\Rules;

use App\Contracts\Actions\Rules\ValidatesAgainstRule;
use App\DataTransferObjects\ValidationResultDto;
use App\Enums\VerificationResult;
use App\Exceptions\FileProcessException;
use App\Exceptions\FileVerificationException;
use Illuminate\Support\Arr;
use Throwable;

class ValidateSignatureHash implements ValidatesAgainstRule
{
    private const HASHING_ALGO = 'sha256';

    public function __construct(private string $errorCode = VerificationResult::InvalidSignature->value)
    {
    }

    public function __invoke(array $data): ValidationResultDto
    {
        $isValid = $this->hasValidSignature($data);

        return new ValidationResultDto($isValid, $isValid ? null : $this->errorCode);
    }

    private function hasValidSignature(array $data): bool
    {
        $signatureTargetHash = $data['signature']['targetHash'] ?? '';

        if (empty($signatureTargetHash)) {
            return false;
        }

        try {
            $flattenProperties = $this->flattenArrayUsingDotNotation($data['data']);
            $hashedProperties = $this->hashKeyValuePairs($flattenProperties);
            sort($hashedProperties, SORT_STRING);

            $hashedPropertiesJson = $this->ensureJsonEncoded($hashedProperties);
            $targetHash = hash(self::HASHING_ALGO, $hashedPropertiesJson);
        } catch (Throwable $e) {
            throw FileVerificationException::hashSignatureFailed();
        }

        return $targetHash === $signatureTargetHash;
    }

    private function flattenArrayUsingDotNotation(array $data): array
    {
        return Arr::dot($data);
    }

    private function hashKeyValuePairs(array $properties): array
    {
        $hashedValues = [];

        foreach ($properties as $key => $value) {
            $jsonPair = $this->ensureJsonEncoded([$key => $value]);
            $hashedValues[] = hash(self::HASHING_ALGO, $jsonPair);
        }

        return $hashedValues;
    }

    /**
     * @throws FileProcessException
     */
    private function ensureJsonEncoded(array $data): string
    {
        $json = json_encode($data);
        if ($json === false) {
            throw FileProcessException::encodeJsonFailed();
        }

        return $json;
    }
}
