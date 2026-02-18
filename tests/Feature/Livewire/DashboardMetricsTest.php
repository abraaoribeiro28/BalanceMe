<?php

declare(strict_types=1);

use App\Livewire\Dashboard;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

use function Pest\Livewire\livewire;

test('dashboard computes totals and current month metrics correctly', function () {
    $now = Carbon::create(2026, 2, 15, 12, 0, 0);
    Carbon::setTestNow($now);

    try {
        $user = User::factory()->create();

        Transaction::create([
            'name' => 'Salario fevereiro',
            'type' => 'Receita',
            'date' => '2026-02-10',
            'amount' => 'R$ 1.500,00',
            'user_id' => $user->id,
            'card_id' => null,
            'category_id' => null,
        ]);

        Transaction::create([
            'name' => 'Mercado fevereiro',
            'type' => 'Despesa',
            'date' => '2026-02-11',
            'amount' => 'R$ 750,00',
            'user_id' => $user->id,
            'card_id' => null,
            'category_id' => null,
        ]);

        Transaction::create([
            'name' => 'Salario janeiro',
            'type' => 'Receita',
            'date' => '2026-01-10',
            'amount' => 'R$ 1.000,00',
            'user_id' => $user->id,
            'card_id' => null,
            'category_id' => null,
        ]);

        Transaction::create([
            'name' => 'Aluguel janeiro',
            'type' => 'Despesa',
            'date' => '2026-01-12',
            'amount' => 'R$ 500,00',
            'user_id' => $user->id,
            'card_id' => null,
            'category_id' => null,
        ]);

        Transaction::create([
            'name' => 'Bico dezembro',
            'type' => 'Receita',
            'date' => '2025-12-10',
            'amount' => 'R$ 200,00',
            'user_id' => $user->id,
            'card_id' => null,
            'category_id' => null,
        ]);

        Transaction::create([
            'name' => 'Internet dezembro',
            'type' => 'Despesa',
            'date' => '2025-12-15',
            'amount' => 'R$ 50,00',
            'user_id' => $user->id,
            'card_id' => null,
            'category_id' => null,
        ]);

        $this->actingAs($user);

        livewire(Dashboard::class)
            ->assertSet('lastUpdated', '15/02/2026')
            ->assertSet('incomeTotal', '1.500,00')
            ->assertSet('expenseTotal', '750,00')
            ->assertSet('totalBalance', '1.400,00');
    } finally {
        Carbon::setTestNow();
    }
});
