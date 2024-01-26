<?php

declare(strict_types=1);

use App\Enums\FileType;

it('returns the correct values', function () {
    $expectedValues = ['application/json'];

    expect(FileType::values())->toBe($expectedValues);
});

it('Returns the correct value for Json', function () {
    expect(FileType::Json->value)->toBe('application/json');
});