<?php

declare(strict_types=1);

use App\Traits\EnumValues;

it('returns the correct values', function () {
    enum FakeEnum: string
    {
        use EnumValues;

        case Value1 = 'value1';
        case Value2 = 'value2';
        case Value3 = 'value3';
    }

    $expectedValues = ['value1', 'value2', 'value3'];

    expect(FakeEnum::values())->toBe($expectedValues);
});
