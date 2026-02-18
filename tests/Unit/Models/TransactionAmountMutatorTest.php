<?php

declare(strict_types=1);

use App\Models\Transaction;

test('transaction amount mutator accepts decimal strings without changing scale', function () {
    $transaction = new Transaction();
    $transaction->amount = '1500.00';

    expect($transaction->getAttributes()['amount'])->toBe('1500.00');
});

test('transaction amount mutator normalizes brl format', function () {
    $transaction = new Transaction();
    $transaction->amount = 'R$ 1.500,00';

    expect($transaction->getAttributes()['amount'])->toBe('1500.00');
});

test('transaction amount mutator throws for invalid values', function () {
    $transaction = new Transaction();

    expect(fn () => $transaction->amount = 'abc')
        ->toThrow(InvalidArgumentException::class);
});
