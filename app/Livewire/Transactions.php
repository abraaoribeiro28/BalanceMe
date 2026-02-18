<?php

namespace App\Livewire;

use App\Models\Transaction;
use Flux\Flux;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Throwable;

class Transactions extends Component
{
    use WithPagination, WithoutUrlPagination;

    public ?int $transactionToDeleteId = null;
    public string $transactionToDeleteName = '';

    /**
     * Reset pagination when transaction data changes.
     */
    #[On('transaction-saved')]
    public function refreshList(): void
    {
        $this->resetPage();
    }

    /**
     * Dispatch the edit event for an owned transaction.
     */
    public function editTransaction(int $transactionId): void
    {
        $transaction = $this->findOwnedTransaction($transactionId);

        if ($transaction === null) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Transação não encontrada.'
            );

            return;
        }

        $this->dispatch('edit-transaction', transactionId: $transaction->id);
    }

    /**
     * Open the delete confirmation modal for an owned transaction.
     */
    public function confirmDeleteTransaction(int $transactionId): void
    {
        $transaction = $this->findOwnedTransaction($transactionId);

        if ($transaction === null) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Transação não encontrada.'
            );

            return;
        }

        $this->transactionToDeleteId = $transaction->id;
        $this->transactionToDeleteName = $transaction->name;

        Flux::modal('confirm-delete-transaction')->show();
    }

    /**
     * Close the delete confirmation modal and clear state.
     */
    public function closeDeleteTransactionModal(): void
    {
        $this->resetDeleteTransactionState();
        Flux::modal('confirm-delete-transaction')->close();
    }

    /**
     * Clear the pending delete transaction state.
     */
    public function resetDeleteTransactionState(): void
    {
        $this->transactionToDeleteId = null;
        $this->transactionToDeleteName = '';
    }

    /**
     * Delete the transaction selected in the confirmation modal.
     */
    public function deleteTransactionConfirmed(): void
    {
        if ($this->transactionToDeleteId === null) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Nenhuma transação selecionada para exclusão.'
            );

            return;
        }

        if ($this->removeTransactionById($this->transactionToDeleteId)) {
            $this->closeDeleteTransactionModal();
        }
    }

    /**
     * Delete a transaction by its identifier.
     */
    public function deleteTransaction(int $transactionId): void
    {
        $this->removeTransactionById($transactionId);
    }

    /**
     * Remove a transaction after existence and ownership checks.
     */
    private function removeTransactionById(int $transactionId): bool
    {
        $transaction = $this->findOwnedTransaction($transactionId);

        if ($transaction === null) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Transação não encontrada.'
            );

            return false;
        }

        try {
            $transaction->delete();

            $this->dispatch(
                'notify',
                type: 'success',
                message: 'Transação removida com sucesso!'
            );
            $this->dispatch('transaction-saved');
        } catch (Throwable $exception) {
            Log::error('Ocorreu erro ao remover transação: ' . $exception->getMessage());

            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Ocorreu um erro ao remover a transação.'
            );

            return false;
        }

        return true;
    }

    /**
     * Find a transaction that belongs to the authenticated user.
     */
    private function findOwnedTransaction(int $transactionId): ?Transaction
    {
        return Transaction::query()
            ->where('user_id', auth()->id())
            ->find($transactionId);
    }

    /**
     * Render the paginated transaction list.
     */
    public function render()
    {
        $transactions = Transaction::with('category', 'card')
            ->where('user_id', auth()->id())
            ->orderBy('date', 'desc')
            ->paginate();

        return view('livewire.transactions', compact('transactions'));
    }
}
