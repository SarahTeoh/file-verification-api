<?php

declare(strict_types=1);

use App\Actions\FetchDnsRecord;
use App\Enums\HttpResponseCode;
use App\Exceptions\DnsLookUpException;
use Illuminate\Support\Facades\Http;

it('fetches dns records successfully', function () {
    Http::fake([
        '*' => Http::response([
            'Answer' => [
                [
                    'name' => 'example.com',
                    'type' => 16,
                    'TTL' => 3600,
                    'data' => 'test data',
                ],
            ],
        ]),
    ]);

    $fetchDnsRecords = new FetchDnsRecord();
    $records = $fetchDnsRecords('example.com');

    expect($records)->toBeArray();
    expect($records)->toHaveCount(1);
    expect($records[0]['name'])->toBe('example.com');
    expect($records[0]['type'])->toBe(16);
    expect($records[0]['TTL'])->toBe(3600);
    expect($records[0]['data'])->toBe('test data');
});

it('throws an exception if the dns lookup fails', function () {
    Http::fake([
        '*' => Http::response([], HttpResponseCode::InternalServerError->value),
    ]);

    $fetchDnsRecords = new FetchDnsRecord();

    expect(fn () => $fetchDnsRecords('example.com'))->toThrow(
        DnsLookUpException::class,
        (DnsLookUpException::dnsLookUpFailed())->getMessage()
    );
});

it('throws an exception if the dns lookup returns a 404 status code', function () {
    Http::fake([
        '*' => Http::response([], HttpResponseCode::NotFound->value),
    ]);

    $fetchDnsRecords = new FetchDnsRecord();

    expect(fn () => $fetchDnsRecords('example.com'))->toThrow(
        DnsLookUpException::class,
        (DnsLookUpException::dnsLookUpFailed())->getMessage()
    );
});

it('throws an exception if the dns response is invalid', function () {
    Http::fake([
        '*' => Http::response([]),
    ]);

    $fetchDnsRecords = new FetchDnsRecord();

    expect(fn () => $fetchDnsRecords('example.com'))->toThrow(
        DnsLookUpException::class,
        (DnsLookUpException::invalidDnsResponse())->getMessage()
    );
});

it('throws an exception if the dns response has an empty Answer array', function () {
    Http::fake([
        '*' => Http::response(['Answer' => []]),
    ]);

    $fetchDnsRecords = new FetchDnsRecord();

    expect(fn () => $fetchDnsRecords('example.com'))->toThrow(
        DnsLookUpException::class,
        (DnsLookUpException::invalidDnsResponse())->getMessage()
    );
});

it('throws an exception if a field is missing in an Answer item', function () {
    Http::fake([
        '*' => Http::response([
            'Answer' => [
                [
                    'name' => 'example.com',
                    'type' => 16,
                    'TTL' => 3600,
                    // 'data' field is missing
                ],
            ],
        ]),
    ]);

    $fetchDnsRecords = new FetchDnsRecord();

    expect(fn () => $fetchDnsRecords('example.com'))->toThrow(
        DnsLookUpException::class,
        (DnsLookUpException::invalidDnsResponse())->getMessage()
    );
});
