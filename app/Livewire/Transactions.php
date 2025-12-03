<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\On;

class Transactions extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[On('transaction-saved')]
    public function refreshList(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $transactions = Transaction::with('category', 'card')
            ->where('user_id', auth()->id())
            ->orderBy('date', 'desc')
            ->paginate(1);

        return view('livewire.transactions', compact('transactions'));
    }
}
