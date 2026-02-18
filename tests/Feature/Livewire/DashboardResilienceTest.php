<?php

declare(strict_types=1);

use App\Models\Transaction;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('dashboard renders with transaction without category and card', function () {
    $user = User::factory()->create();

    Transaction::factory()
        ->forUser($user)
        ->expense()
        ->create([
            'name' => 'Compra sem categoria',
            'date' => now()->toDateString(),
            'category_id' => null,
            'card_id' => null,
        ]);

    actingAs($user);

    get(route('dashboard'))
        ->assertOk()
        ->assertSee('Sem categoria');
});
