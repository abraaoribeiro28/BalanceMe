<?php

namespace App\Livewire\Modals;

use App\Models\Card;
use App\Models\Category;
use App\Models\Transaction as TransactionModel;
use App\Rules\Money;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class Transaction extends Component
{
    public string $name = '';
    public string $amount = '';
    public string $type = '';
    public int|string $category_id = '';
    public $card_id = '';
    public string $date = '';
    public string $description = '';

    public array $cards;
    public array $categories;
    public TransactionModel|null $transaction = null;

    /**
     * Get the validation rules for the transaction form.
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
     * Load a transaction into the form and open the edit modal.
     */
    #[On('edit-transaction')]
    public function openForEdit(int $transactionId): void
    {
        $transaction = TransactionModel::query()->find($transactionId);

        if ($transaction === null) {
            $this->dispatch(
                event: 'notify',
                type: 'error',
                message: 'Transação não encontrada.'
            );

            return;
        }

        if (Gate::denies('update', $transaction)) {
            $this->dispatch(
                event: 'notify',
                type: 'error',
                message: 'Você não tem permissão para editar esta transação.'
            );

            return;
        }

        $this->transaction = $transaction;
        $this->name = $transaction->name;
        $this->amount = 'R$ ' . number_format((float) $transaction->amount, 2, ',', '.');
        $this->type = $transaction->type;
        $this->category_id = $transaction->category_id;
        $this->card_id = $transaction->card_id ?? '';
        $this->date = $transaction->date?->format('Y-m-d') ?? '';
        $this->description = (string) ($transaction->description ?? '');
        $this->resetValidation();

        Flux::modal('modal-transaction')->show();
    }

    /**
     * Validate and persist the transaction.
     */
    public function save(): void
    {
        $validated = $this->validate();
        $userId = auth()->id();
        $validated['card_id'] = $validated['card_id'] === '' ? null : $validated['card_id'];

        try {
            if ($this->transaction !== null) {
                if (Gate::denies('update', $this->transaction)) {
                    $this->dispatch(
                        event: 'notify',
                        type: 'error',
                        message: 'Você não tem permissão para editar esta transação.'
                    );

                    return;
                }

                $transaction = $this->transaction;
            } else {
                if (Gate::denies('create', TransactionModel::class)) {
                    $this->dispatch(
                        event: 'notify',
                        type: 'error',
                        message: 'Você não tem permissão para criar transações.'
                    );

                    return;
                }

                $transaction = new TransactionModel();
            }

            $transaction->fill($validated);
            $transaction->user_id = $userId;
            $transaction->save();

            $this->dispatch('transaction-saved');

            $this->dispatch(
                event: 'notify',
                type: 'success',
                message: 'Transação salva com sucesso!'
            );

            Flux::modal('modal-transaction')->close();
        } catch (Throwable $exception) {
            Log::error('Ocorreu erro ao registrar transação: ' . $exception->getMessage());

            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Ocorreu um erro ao salvar a transação.'
            );
        }

        $this->resetForm();
    }

    /**
     * Reset the form state when the modal is closed.
     */
    public function resetForm(): void
    {
        $this->resetExcept('cards', 'categories');
    }
}
