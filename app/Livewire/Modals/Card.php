<?php

namespace App\Livewire\Modals;

use App\Models\Card as CardModel;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class Card extends Component
{
    public string $name = '';

    public CardModel|null $card = null;

    /**
     * Validation rules for card form.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $userId = auth()->id();
        $cardId = $this->card?->id;

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                Rule::unique('cards', 'name')
                    ->where(fn ($query) => $query->where('user_id', $userId))
                    ->ignore($cardId),
            ],
        ];
    }

    #[On('edit-card')]
    public function openForEdit(int $cardId): void
    {
        $card = CardModel::query()->find($cardId);

        if ($card === null) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Cartão não encontrado.',
                duration: 4000
            );

            return;
        }

        if (Gate::denies('update', $card)) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Você não tem permissão para editar este cartão.',
                duration: 4000
            );

            return;
        }

        $this->card = $card;
        $this->name = $card->name;
        $this->resetValidation();

        Flux::modal('modal-cards')->show();
    }

    /**
     * Validate and persist the card.
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

        try {
            if ($this->card !== null) {
                if (Gate::denies('update', $this->card)) {
                    $this->dispatch(
                        'notify',
                        type: 'error',
                        message: 'Você não tem permissão para editar este cartão.',
                        duration: 4000
                    );

                    return;
                }

                $card = $this->card;
            } else {
                if (Gate::denies('create', CardModel::class)) {
                    $this->dispatch(
                        'notify',
                        type: 'error',
                        message: 'Você não tem permissão para criar cartões.',
                        duration: 4000
                    );

                    return;
                }

                $card = new CardModel();
            }

            $card->fill($validated);
            $card->user_id = $userId;
            $card->save();

            $this->dispatch('notify',
                type: 'success',
                message: 'Cartão salvo com sucesso!.',
                duration: 4000
            );
        } catch (Throwable $exception) {
            Log::error('Ocorreu erro ao registrar cartão: ' . $exception->getMessage());
            $this->dispatch('notify',
                type: 'error',
                message: 'Ocorreu um erro ao salvar o cartão.',
                duration: 4000
            );
        }

        $this->resetForm();
        Flux::modals()->close();
        $this->dispatch('load-cards');
    }

    /**
     * Reset the form state when the modal is closed.
     *
     * @return void
     */
    public function resetForm(): void
    {
        $this->reset();
        $this->resetValidation();
    }
}