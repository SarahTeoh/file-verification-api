<?php

declare(strict_types=1);

use App\Enums\HttpResponseCode;

it('returns the correct values', function () {
    $expectedValues = [200, 400, 401, 403, 404, 500];

    $actualValues = array_map(fn ($case) => $case->value, HttpResponseCode::cases());

    expect($actualValues)->toBe($expectedValues);
});

it('returns the correct value for each case', function () {
    expect(HttpResponseCode::Ok->value)->toBe(200);
    expect(HttpResponseCode::BadRequest->value)->toBe(400);
    expect(HttpResponseCode::Unauthorized->value)->toBe(401);
    expect(HttpResponseCode::Forbidden->value)->toBe(403);
    expect(HttpResponseCode::NotFound->value)->toBe(404);
    expect(HttpResponseCode::InternalServerError->value)->toBe(500);
});