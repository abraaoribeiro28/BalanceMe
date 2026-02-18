<?php

declare(strict_types=1);

use App\Livewire\Cards as CardsList;
use App\Livewire\Categories as CategoriesList;
use App\Livewire\Modals\Card as CardModal;
use App\Livewire\Modals\Category as CategoryModal;
use App\Livewire\Modals\Transaction as TransactionModal;
use App\Livewire\Transactions as TransactionsList;
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

test('transactions list dispatches edit event for own transaction', function () {
    $user = User::factory()->create();
    $category = Category::factory()->forUser($user)->expense()->create();
    $transaction = Transaction::factory()->forUser($user)->expense()->create([
        'category_id' => $category->id,
        'card_id' => null,
    ]);

    $this->actingAs($user);

    livewire(TransactionsList::class)
        ->call('editTransaction', $transaction->id)
        ->assertDispatched('edit-transaction');
});

test('transactions list cannot dispatch edit event for transaction from another user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherCategory = Category::factory()->forUser($otherUser)->expense()->create();
    $foreignTransaction = Transaction::factory()->forUser($otherUser)->expense()->create([
        'category_id' => $otherCategory->id,
        'card_id' => null,
    ]);

    $this->actingAs($user);

    livewire(TransactionsList::class)
        ->call('editTransaction', $foreignTransaction->id)
        ->assertNotDispatched('edit-transaction')
        ->assertDispatched('notify');
});

test('cards list dispatches edit event for own card', function () {
    $user = User::factory()->create();
    $card = Card::factory()->forUser($user)->create();

    $this->actingAs($user);

    livewire(CardsList::class)
        ->call('editCard', $card->id)
        ->assertDispatched('edit-card');
});

test('categories list dispatches edit event for own category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->forUser($user)->expense()->create();

    $this->actingAs($user);

    livewire(CategoriesList::class)
        ->call('editCategory', $category->id)
        ->assertDispatched('edit-category');
});

test('card modal loads own card data when opening edit', function () {
    $user = User::factory()->create();
    $card = Card::factory()->forUser($user)->create(['name' => 'Nubank']);

    $this->actingAs($user);

    livewire(CardModal::class)
        ->call('openForEdit', $card->id)
        ->assertSet('name', 'Nubank')
        ->assertDispatched('modal-show');
});

test('category modal loads own category data when opening edit', function () {
    $user = User::factory()->create();
    $category = Category::factory()->forUser($user)->expense()->create([
        'name' => 'Mercado',
        'type' => 'Despesa',
    ]);

    $this->actingAs($user);

    livewire(CategoryModal::class)
        ->call('openForEdit', $category->id)
        ->assertSet('name', 'Mercado')
        ->assertSet('type', 'Despesa')
        ->assertDispatched('modal-show');
});

test('transaction modal loads own transaction data when opening edit', function () {
    $user = User::factory()->create();
    $category = Category::factory()->forUser($user)->expense()->create();
    $card = Card::factory()->forUser($user)->create();
    $transaction = Transaction::factory()->forUser($user)->expense()->create([
        'name' => 'Internet',
        'amount' => 199.90,
        'category_id' => $category->id,
        'card_id' => $card->id,
        'date' => now()->toDateString(),
    ]);

    $this->actingAs($user);

    livewire(TransactionModal::class)
        ->call('openForEdit', $transaction->id)
        ->assertSet('name', 'Internet')
        ->assertSet('type', 'Despesa')
        ->assertSet('category_id', $category->id)
        ->assertSet('card_id', $card->id)
        ->assertDispatched('modal-show');
});

test('transactions list cannot delete transaction from another user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $otherCategory = Category::factory()->forUser($otherUser)->expense()->create();

    $foreignTransaction = Transaction::factory()
        ->forUser($otherUser)
        ->expense()
        ->create([
            'category_id' => $otherCategory->id,
            'card_id' => null,
        ]);

    $this->actingAs($user);

    livewire(TransactionsList::class)
        ->call('deleteTransaction', $foreignTransaction->id)
        ->assertDispatched('notify');

    expect(Transaction::query()->whereKey($foreignTransaction->id)->exists())->toBeTrue();
});

test('transactions list can delete own transaction', function () {
    $user = User::factory()->create();
    $category = Category::factory()->forUser($user)->expense()->create();

    $transaction = Transaction::factory()
        ->forUser($user)
        ->expense()
        ->create([
            'category_id' => $category->id,
            'card_id' => null,
        ]);

    $this->actingAs($user);

    livewire(TransactionsList::class)
        ->call('deleteTransaction', $transaction->id)
        ->assertDispatched('transaction-saved');

    expect(Transaction::query()->whereKey($transaction->id)->exists())->toBeFalse();
});

test('cards list cannot delete card from another user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $foreignCard = Card::factory()->forUser($otherUser)->create();

    $this->actingAs($user);

    livewire(CardsList::class)
        ->call('deleteCard', $foreignCard->id)
        ->assertDispatched('notify');

    expect(Card::query()->whereKey($foreignCard->id)->exists())->toBeTrue();
});

test('cards list can delete own card', function () {
    $user = User::factory()->create();
    $card = Card::factory()->forUser($user)->create();

    $this->actingAs($user);

    livewire(CardsList::class)
        ->call('deleteCard', $card->id)
        ->assertDispatched('transaction-saved');

    expect(Card::query()->whereKey($card->id)->exists())->toBeFalse();
});

test('categories list cannot delete category from another user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $foreignCategory = Category::factory()->forUser($otherUser)->expense()->create();

    $this->actingAs($user);

    livewire(CategoriesList::class)
        ->call('deleteCategory', $foreignCategory->id)
        ->assertDispatched('notify');

    expect(Category::query()->whereKey($foreignCategory->id)->exists())->toBeTrue();
});

test('categories list can delete own category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->forUser($user)->expense()->create();

    $this->actingAs($user);

    livewire(CategoriesList::class)
        ->call('deleteCategory', $category->id)
        ->assertDispatched('transaction-saved');

    expect(Category::query()->whereKey($category->id)->exists())->toBeFalse();
});

test('cards list opens delete confirmation modal for own card', function () {
    $user = User::factory()->create();
    $card = Card::factory()->forUser($user)->create(['name' => 'Nubank']);

    $this->actingAs($user);

    livewire(CardsList::class)
        ->call('confirmDeleteCard', $card->id)
        ->assertDispatched('modal-show')
        ->assertSet('cardToDeleteId', $card->id)
        ->assertSet('cardToDeleteName', 'Nubank');
});

test('categories list opens delete confirmation modal for own category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->forUser($user)->expense()->create(['name' => 'Mercado']);

    $this->actingAs($user);

    livewire(CategoriesList::class)
        ->call('confirmDeleteCategory', $category->id)
        ->assertDispatched('modal-show')
        ->assertSet('categoryToDeleteId', $category->id)
        ->assertSet('categoryToDeleteName', 'Mercado');
});

test('transactions list opens delete confirmation modal for own transaction', function () {
    $user = User::factory()->create();
    $category = Category::factory()->forUser($user)->expense()->create();
    $transaction = Transaction::factory()->forUser($user)->expense()->create([
        'name' => 'Internet',
        'category_id' => $category->id,
        'card_id' => null,
    ]);

    $this->actingAs($user);

    livewire(TransactionsList::class)
        ->call('confirmDeleteTransaction', $transaction->id)
        ->assertDispatched('modal-show')
        ->assertSet('transactionToDeleteId', $transaction->id)
        ->assertSet('transactionToDeleteName', 'Internet');
});
