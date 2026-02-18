<?php

declare(strict_types=1);

use App\Rules\Money;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

uses(TestCase::class);

test('money rule validates decimal strings and brl format', function (string $value) {
    $validator = Validator::make(
        ['amount' => $value],
        ['amount' => [new Money()]],
    );

    expect($validator->passes())->toBeTrue();
})->with([
    ['1500.00'],
    ['R$ 1.500,00'],
]);

test('money rule rejects invalid or non-positive values', function (string $value) {
    $validator = Validator::make(
        ['amount' => $value],
        ['amount' => [new Money()]],
    );

    expect($validator->fails())->toBeTrue();
})->with([
    ['abc'],
    ['0'],
    ['R$ 0,00'],
    ['-10'],
]);
