<?php

namespace App\Livewire;

use App\Models\Card;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class Cards extends Component
{
    public Collection $cards;
    public ?int $cardToDeleteId = null;
    public string $cardToDeleteName = '';

    #[On('load-cards')]
    public function loadCards(): void
    {
        $this->cards = Card::where('user_id', auth()->id())->get();
    }

    public function mount(): void
    {
        $this->loadCards();
    }

    public function editCard(int $cardId): void
    {
        $card = Card::query()->find($cardId);

        if ($card === null) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Cartão não encontrado.',
                duration: 4000
            );

            return;
        }

        if (Gate::denies('update', $card)) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Você não tem permissão para editar este cartão.',
                duration: 4000
            );

            return;
        }

        $this->dispatch('edit-card', cardId: $card->id);
    }

    public function confirmDeleteCard(int $cardId): void
    {
        $card = Card::query()->find($cardId);

        if ($card === null) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Cartão não encontrado.',
                duration: 4000
            );

            return;
        }

        if (Gate::denies('delete', $card)) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Você não tem permissão para remover este cartão.',
                duration: 4000
            );

            return;
        }

        $this->cardToDeleteId = $card->id;
        $this->cardToDeleteName = $card->name;

        Flux::modal('confirm-delete-card')->show();
    }

    public function closeDeleteCardModal(): void
    {
        $this->resetDeleteCardState();
        Flux::modal('confirm-delete-card')->close();
    }

    public function resetDeleteCardState(): void
    {
        $this->cardToDeleteId = null;
        $this->cardToDeleteName = '';
    }

    public function deleteCardConfirmed(): void
    {
        if ($this->cardToDeleteId === null) {
            $this->dispatch('notify',
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

    public function deleteCard(int $cardId): void
    {
        $this->removeCardById($cardId);
    }

    private function removeCardById(int $cardId): bool
    {
        $card = Card::query()->find($cardId);

        if ($card === null) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Cartão não encontrado.',
                duration: 4000
            );

            return false;
        }

        if (Gate::denies('delete', $card)) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Você não tem permissão para remover este cartão.',
                duration: 4000
            );

            return false;
        }

        try {
            $card->delete();

            $this->dispatch('notify',
                type: 'success',
                message: 'Cartão removido com sucesso!',
                duration: 4000
            );

            $this->loadCards();
            $this->dispatch('transaction-saved');
        } catch (Throwable $exception) {
            Log::error('Ocorreu erro ao remover cartão: ' . $exception->getMessage());
            $this->dispatch('notify',
                type: 'error',
                message: 'Ocorreu um erro ao remover o cartão.',
                duration: 4000
            );

            return false;
        }

        return true;
    }
}