<?php

declare(strict_types=1);

use App\Models\Card;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\QueryException;

test('card name must be unique per user', function () {
    $user = User::factory()->create();

    Card::create([
        'name' => 'Nubank',
        'user_id' => $user->id,
    ]);

    expect(fn () => Card::create([
        'name' => 'Nubank',
        'user_id' => $user->id,
    ]))->toThrow(QueryException::class);
});

test('card name can repeat for different users', function () {
    $firstUser = User::factory()->create();
    $secondUser = User::factory()->create();

    Card::create([
        'name' => 'Nubank',
        'user_id' => $firstUser->id,
    ]);

    Card::create([
        'name' => 'Nubank',
        'user_id' => $secondUser->id,
    ]);

    expect(Card::query()->where('name', 'Nubank')->count())->toBe(2);
});

test('category name must be unique per user', function () {
    $user = User::factory()->create();

    Category::create([
        'name' => 'Lazer',
        'type' => 'Despesa',
        'user_id' => $user->id,
    ]);

    expect(fn () => Category::create([
        'name' => 'Lazer',
        'type' => 'Receita',
        'user_id' => $user->id,
    ]))->toThrow(QueryException::class);
});

test('category name can repeat for different users', function () {
    $firstUser = User::factory()->create();
    $secondUser = User::factory()->create();

    Category::create([
        'name' => 'Lazer',
        'type' => 'Despesa',
        'user_id' => $firstUser->id,
    ]);

    Category::create([
        'name' => 'Lazer',
        'type' => 'Despesa',
        'user_id' => $secondUser->id,
    ]);

    expect(Category::query()->where('name', 'Lazer')->count())->toBe(2);
});
