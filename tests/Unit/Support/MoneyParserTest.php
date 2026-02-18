<?php

declare(strict_types=1);

use App\Support\MoneyParser;

test('money parser normalizes supported money formats', function (mixed $input, string $expected) {
    expect(MoneyParser::toDecimal($input))->toBe($expected);
})->with([
    ['R$ 1.500,00', '1500.00'],
    ['1500.00', '1500.00'],
    ['1,500.00', '1500.00'],
    ['1.234,56', '1234.56'],
    ['1.234', '1234.00'],
    ['1234', '1234.00'],
    [1500.5, '1500.50'],
]);

test('money parser returns null for invalid money values', function (mixed $input) {
    expect(MoneyParser::toDecimal($input))->toBeNull();
})->with([
    [''],
    ['abc'],
    [null],
    [false],
]);
