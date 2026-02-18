<?php

namespace App\Livewire;

use App\Models\Card;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class Cards extends Component
{
    public Collection $cards;
    public ?int $cardToDeleteId = null;
    public string $cardToDeleteName = '';

    /**
     * Load all cards for the authenticated user.
     */
    #[On('load-cards')]
    public function loadCards(): void
    {
        $this->cards = Card::where('user_id', auth()->id())->get();
    }

    /**
     * Initialize the component card collection.
     */
    public function mount(): void
    {
        $this->loadCards();
    }

    /**
     * Dispatch the edit event for an owned card.
     */
    public function editCard(int $cardId): void
    {
        $card = $this->findOwnedCard($cardId);

        if ($card === null) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Cartão não encontrado.',
                duration: 4000
            );

            return;
        }

        $this->dispatch('edit-card', cardId: $card->id);
    }

    /**
     * Open the delete confirmation modal for an owned card.
     */
    public function confirmDeleteCard(int $cardId): void
    {
        $card = $this->findOwnedCard($cardId);

        if ($card === null) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Cartão não encontrado.',
                duration: 4000
            );

            return;
        }

        $this->cardToDeleteId = $card->id;
        $this->cardToDeleteName = $card->name;

        Flux::modal('confirm-delete-card')->show();
    }

    /**
     * Close the delete confirmation modal and clear state.
     */
    public function closeDeleteCardModal(): void
    {
        $this->resetDeleteCardState();
        Flux::modal('confirm-delete-card')->close();
    }

    /**
     * Clear the pending delete card state.
     */
    public function resetDeleteCardState(): void
    {
        $this->cardToDeleteId = null;
        $this->cardToDeleteName = '';
    }

    /**
     * Delete the card selected in the confirmation modal.
     */
    public function deleteCardConfirmed(): void
    {
        if ($this->cardToDeleteId === null) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Nenhum cartão selecionado para exclusão.',
                duration: 4000
            );

            return;
        }

        if ($this->removeCardById($this->cardToDeleteId)) {
            $this->closeDeleteCardModal();
        }
    }

    /**
     * Delete a card by its identifier.
     */
    public function deleteCard(int $cardId): void
    {
        $this->removeCardById($cardId);
    }

    /**
     * Remove a card after existence and ownership checks.
     */
    private function removeCardById(int $cardId): bool
    {
        $card = $this->findOwnedCard($cardId);

        if ($card === null) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Cartão não encontrado.',
                duration: 4000
            );

            return false;
        }

        try {
            $card->delete();

            $this->dispatch(
                'notify',
                type: 'success',
                message: 'Cartão removido com sucesso!',
                duration: 4000
            );

            $this->loadCards();
            $this->dispatch('transaction-saved');
        } catch (Throwable $exception) {
            Log::error('Ocorreu erro ao remover cartão: ' . $exception->getMessage());
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Ocorreu um erro ao remover o cartão.',
                duration: 4000
            );

            return false;
        }

        return true;
    }

    /**
     * Find a card that belongs to the authenticated user.
     */
    private function findOwnedCard(int $cardId): ?Card
    {
        return Card::query()
            ->where('user_id', auth()->id())
            ->find($cardId);
    }
}
