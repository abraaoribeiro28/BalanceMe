<?php

namespace App\Livewire\Modals;

use App\Models\Card;
use App\Rules\Money;
use Flux\Flux;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
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
    public int|string $category_id = '';
    public $card_id = '';
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
        $userId = auth()->id();

        return [
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'amount' => [new Money()],
            'type' => ['required', Rule::in(['Receita', 'Despesa'])],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(
                    fn ($query) => $query->where('user_id', $userId)
                ),
            ],
            'card_id' => [
                'nullable',
                Rule::exists('cards', 'id')->where(
                    fn ($query) => $query->where('user_id', $userId)
                ),
            ],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'min:3', 'max:255'],
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
        $userId = auth()->id();
        $validated['user_id'] = $userId;
        $validated['card_id'] = $validated['card_id'] === '' ? null : $validated['card_id'];

        try {
            if ($this->transaction !== null) {
                $transaction = TransactionModel::query()
                    ->whereKey($this->transaction->id)
                    ->where('user_id', $userId)
                    ->first();

                if ($transaction === null) {
                    $this->dispatch(
                        event: 'notify',
                        type: 'error',
                        message: 'Você não tem permissão para editar esta transação.'
                    );

                    return;
                }
            } else {
                $transaction = new TransactionModel();
            }

            $transaction->fill($validated);
            $transaction->save();

            $this->dispatch('transaction-saved');

            $this->dispatch(
                event: 'notify',
                type: 'success',
                message: 'Transação salva com sucesso!'
            );

            Flux::modals()->close();
        } catch (Throwable $exception) {
            Log::error('Ocorreu erro ao registrar transação: ' . $exception->getMessage());

            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Ocorreu um erro ao salvar a transação.'
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
