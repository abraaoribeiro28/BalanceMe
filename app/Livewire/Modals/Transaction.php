<?php

namespace App\Livewire\Modals;

use App\Models\Card;
use App\Rules\Money;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\On;
use App\Models\Transaction as TransactionModel;
use Throwable;

class Transaction extends Component
{
    public string $name;
    public string $amount;
    public string $type;
    public int $category_id;
    public int $card_id;
    public $date;
    public string $description;

    public array $cards;
    public array $categories;
    public TransactionModel|null $transaction = null;

    /**
     * Get the validation rules for the transaction form.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:50',
            'amount' => new Money(),
            'type' => 'required|string|min:3|max:50',
            'category_id' => 'required|exists:categories,id',
            'card_id' => 'nullable|exists:cards,id',
            'date' => 'required|date',
            'description' => 'nullable|string|min:3|max:255',
        ];
    }

    /**
     * Loads the user's cards and categories into local arrays
     * to populate selection fields in the form.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->cards = Card::where('user_id', auth()->id())
            ->pluck('name', 'id')
            ->toArray();

        $this->categories = Category::where('user_id', auth()->id())
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Validate and persist the transaction.
     *
     * @return void
     *
     * @throws ValidationException
     * @throws Throwable
     */
    public function save(): void
    {
        $validated = $this->validate();
        $validated['user_id'] = auth()->id();

        try {
            TransactionModel::updateOrCreate(
                ['id' => $this->transaction?->id],
                $validated
            );

            $this->dispatch('transaction-saved');

            $this->dispatch('notify',
                type: 'success',
                content: 'Transação salva com sucesso!.',
                duration: 4000
            );
        } catch (Throwable $exception) {
            Log::error('Ocorreu erro ao registrar transação: ' . $exception->getMessage());
            $this->dispatch('notify',
                type: 'error',
                content: 'Ocorreu um erro ao salvar a transação.',
                duration: 4000
            );
        }

        $this->dispatch('close-modal', id: 'modal-transaction');
    }

    /**
     * Reset the form state when the modal is closed.
     *
     * @return void
     */
    #[On('close-modal')]
    public function resetForm(): void
    {
        $this->resetExcept('cards', 'categories');
    }
}
