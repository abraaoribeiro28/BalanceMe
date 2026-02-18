<?php

declare(strict_types=1);

use App\Models\Card;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

test('card policy allows owner update and denies foreign update', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $ownedCard = Card::factory()->forUser($user)->create();
    $foreignCard = Card::factory()->forUser($otherUser)->create();

    expect(Gate::forUser($user)->allows('create', Card::class))->toBeTrue();
    expect(Gate::forUser($user)->allows('update', $ownedCard))->toBeTrue();
    expect(Gate::forUser($user)->denies('update', $foreignCard))->toBeTrue();
    expect(Gate::forUser($user)->allows('delete', $ownedCard))->toBeTrue();
    expect(Gate::forUser($user)->denies('delete', $foreignCard))->toBeTrue();
});

test('category policy allows owner update and denies foreign update', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $ownedCategory = Category::factory()->forUser($user)->expense()->create();
    $foreignCategory = Category::factory()->forUser($otherUser)->expense()->create();

    expect(Gate::forUser($user)->allows('create', Category::class))->toBeTrue();
    expect(Gate::forUser($user)->allows('update', $ownedCategory))->toBeTrue();
    expect(Gate::forUser($user)->denies('update', $foreignCategory))->toBeTrue();
    expect(Gate::forUser($user)->allows('delete', $ownedCategory))->toBeTrue();
    expect(Gate::forUser($user)->denies('delete', $foreignCategory))->toBeTrue();
});

test('transaction policy allows owner update and denies foreign update', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $ownedCategory = Category::factory()->forUser($user)->expense()->create();
    $foreignCategory = Category::factory()->forUser($otherUser)->expense()->create();

    $ownedTransaction = Transaction::factory()
        ->forUser($user)
        ->expense()
        ->create([
            'category_id' => $ownedCategory->id,
            'card_id' => null,
        ]);

    $foreignTransaction = Transaction::factory()
        ->forUser($otherUser)
        ->expense()
        ->create([
            'category_id' => $foreignCategory->id,
            'card_id' => null,
        ]);

    expect(Gate::forUser($user)->allows('create', Transaction::class))->toBeTrue();
    expect(Gate::forUser($user)->allows('update', $ownedTransaction))->toBeTrue();
    expect(Gate::forUser($user)->denies('update', $foreignTransaction))->toBeTrue();
    expect(Gate::forUser($user)->allows('delete', $ownedTransaction))->toBeTrue();
    expect(Gate::forUser($user)->denies('delete', $foreignTransaction))->toBeTrue();
});
