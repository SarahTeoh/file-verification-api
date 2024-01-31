<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\Actions\ProcessesFile;
use App\Exceptions\FileProcessException;

class ProcessJsonFile implements ProcessesFile
{
    public function __invoke(string $fileContent): array
    {
        $jsonArray = $this->decodeJsonIntoArray($fileContent);

        return $this->format($jsonArray);
    }

    /**
     * @throws FileProcessException
     */
    private function decodeJsonIntoArray(string $jsonString): array
    {
        $decodedArray = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw FileProcessException::decodeJsonFailed();
        }

        return $decodedArray;
    }

    private function format(array $jsonArray): array
    {
        return [
            'data' => [
                'id' => $jsonArray['data']['id'] ?? '',
                'name' => $jsonArray['data']['name'] ?? '',
                'recipient' => [
                    'name' => $jsonArray['data']['recipient']['name'] ?? '',
                    'email' => $jsonArray['data']['recipient']['email'] ?? '',
                ],
                'issuer' => [
                    'name' => $jsonArray['data']['issuer']['name'] ?? '',
                    'identityProof' => [
                        'type' => $jsonArray['data']['issuer']['identityProof']['type'] ?? '',
                        'key' => $jsonArray['data']['issuer']['identityProof']['key'] ?? '',
                        'location' => $jsonArray['data']['issuer']['identityProof']['location'] ?? '',
                    ],
                ],
                'issued' => $jsonArray['data']['issued'] ?? '',
            ],
            'signature' => [
                'type' => $jsonArray['signature']['type'] ?? '',
                'targetHash' => $jsonArray['signature']['targetHash'] ?? '',
            ],
        ];
    }
}
