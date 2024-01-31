<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\Actions\FetchesDnsRecord;
use App\Exceptions\DnsLookUpException;
use Illuminate\Support\Facades\Http;

class FetchDnsRecord implements FetchesDnsRecord
{
    private const GOOGLE_DNS_API = 'https://dns.google/resolve';

    private const GOOGLE_DNS_TYPE = 'TXT';

    public function __invoke(string $domain): array
    {
        $response = Http::get(self::GOOGLE_DNS_API, [
            'name' => $domain,
            'type' => self::GOOGLE_DNS_TYPE,
        ]);

        if (! $response->successful()) {
            throw DnsLookUpException::dnsLookUpFailed();
        }

        $jsonResponse = $response->json();
        $validDnsResponse = $this->extractValidDnsResponse($jsonResponse);

        return $validDnsResponse;
    }

    private function extractValidDnsResponse(array $dnsResponse): array
    {
        $requiredFields = ['name', 'type', 'TTL', 'data'];
        if (! isset($dnsResponse['Answer']) || empty($dnsResponse['Answer'])) {
            throw DnsLookUpException::invalidDnsResponse();
        }

        $dnsResponseAnswer = $dnsResponse['Answer'];
        foreach ($dnsResponseAnswer as $answer) {
            if (array_diff_key(array_flip($requiredFields), $answer)) {
                throw DnsLookUpException::invalidDnsResponse();
            }
        }

        return $dnsResponseAnswer;
    }
}
