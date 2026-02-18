<?php

declare(strict_types=1);

use App\Livewire\Modals\Card as CardModal;
use App\Livewire\Modals\Category as CategoryModal;
use App\Livewire\Modals\Transaction as TransactionModal;
use App\Models\Card;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;

use function Pest\Livewire\livewire;

test('transaction modal blocks category and card from another user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $foreignCategory = Category::factory()->forUser($otherUser)->expense()->create();
    $foreignCard = Card::factory()->forUser($otherUser)->create();

    $this->actingAs($user);

    livewire(TransactionModal::class)
        ->set('name', 'Compra')
        ->set('amount', 'R$ 10,00')
        ->set('type', 'Despesa')
        ->set('category_id', $foreignCategory->id)
        ->set('card_id', $foreignCard->id)
        ->set('date', now()->toDateString())
        ->call('save')
        ->assertHasErrors(['category_id', 'card_id']);

    expect(Transaction::query()->where('user_id', $user->id)->count())->toBe(0);
});

test('transaction modal cannot update transaction from another user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $userCategory = Category::factory()->forUser($user)->expense()->create();
    $otherUserCategory = Category::factory()->forUser($otherUser)->expense()->create();

    $foreignTransaction = Transaction::factory()
        ->forUser($otherUser)
        ->expense()
        ->create([
            'name' => 'Original',
            'amount' => 100,
            'date' => now()->toDateString(),
            'category_id' => $otherUserCategory->id,
            'card_id' => null,
        ]);

    $this->actingAs($user);

    livewire(TransactionModal::class, ['transaction' => $foreignTransaction])
        ->set('name', 'Tentativa')
        ->set('amount', 'R$ 99,00')
        ->set('type', 'Despesa')
        ->set('category_id', $userCategory->id)
        ->set('card_id', '')
        ->set('date', now()->toDateString())
        ->call('save')
        ->assertDispatched('notify');

    $foreignTransaction->refresh();

    expect($foreignTransaction->name)->toBe('Original');
    expect($foreignTransaction->user_id)->toBe($otherUser->id);
    expect(Transaction::query()->where('user_id', $user->id)->count())->toBe(0);
});

test('card modal cannot update card from another user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $foreignCard = Card::factory()->forUser($otherUser)->create(['name' => 'Card Original']);

    $this->actingAs($user);

    livewire(CardModal::class, ['card' => $foreignCard])
        ->set('name', 'Card Changed')
        ->call('save')
        ->assertDispatched('notify');

    $foreignCard->refresh();

    expect($foreignCard->name)->toBe('Card Original');
    expect($foreignCard->user_id)->toBe($otherUser->id);
});

test('category modal cannot update category from another user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $foreignCategory = Category::factory()->forUser($otherUser)->expense()->create(['name' => 'Category Original']);

    $this->actingAs($user);

    livewire(CategoryModal::class, ['category' => $foreignCategory])
        ->set('name', 'Category Changed')
        ->set('type', 'Despesa')
        ->call('save')
        ->assertDispatched('notify');

    $foreignCategory->refresh();

    expect($foreignCategory->name)->toBe('Category Original');
    expect($foreignCategory->user_id)->toBe($otherUser->id);
});
