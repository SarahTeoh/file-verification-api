<?php

declare(strict_types=1);

namespace App\Actions\Rules;

use App\Actions\FetchDnsRecord;
use App\Contracts\Actions\Rules\ValidatesAgainstRule;
use App\DataTransferObjects\ValidationResultDto;
use App\Enums\VerificationResult;

class ValidateIssuer implements ValidatesAgainstRule
{
    private const DNS_RECORD_TXT_TYPE = 16;

    public function __construct(
        private FetchDnsRecord $fetchDnsRecord,
        private string $errorCode = VerificationResult::InvalidIssuer->value
    ) {
    }

    public function __invoke(array $data): ValidationResultDto
    {
        $isValid = $this->hasValidIssuer($data);

        return new ValidationResultDto($isValid, $isValid ? null : $this->errorCode);
    }

    private function hasValidIssuer(array $data): bool
    {
        if (! isset($data['data']['issuer']['name'], $data['data']['issuer']['identityProof'])) {
            return false;
        }

        $issuerKey = $data['data']['issuer']['identityProof']['key'];
        $domain = $data['data']['issuer']['identityProof']['location'];

        if (empty($issuerKey) || empty($domain)) {
            return false;
        }

        return $this->isKeyInDnsTxtRecord($issuerKey, $domain);
    }

    private function isKeyInDnsTxtRecord(string $key, string $domain): bool
    {
        $dnsRecords = ($this->fetchDnsRecord)($domain);
        foreach ($dnsRecords as $record) {
            if ($record['type'] === self::DNS_RECORD_TXT_TYPE && strpos($record['data'], $key) !== false) {
                return true;
            }
        }

        return false;
    }
}
