<?php

namespace App\Livewire\Modals;

use App\Models\Card as CardModel;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class Card extends Component
{
    public string $name;

    public CardModel|null $card = null;

    /**
     * Validation rules for card form.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:50',
        ];
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
        $validated['user_id'] = auth()->id();

        try {
            CardModel::updateOrCreate(
                ['id' => $this->card?->id],
                $validated
            );

            $this->dispatch('notify',
                type: 'success',
                content: 'Cartão salvo com sucesso!.',
                duration: 4000
            );
        } catch (Throwable $exception) {
            $this->dispatch('notify',
                type: 'error',
                content: 'Ocorreu um erro ao salvar o cartão.',
                duration: 4000
            );
        }

        $this->dispatch('close-modal', id: 'modal-card');
    }

    /**
     * Reset the form state when the modal is closed.
     *
     * @return void
     */
    #[On('close-modal')]
    public function resetForm(): void
    {
        $this->reset();
    }
}

