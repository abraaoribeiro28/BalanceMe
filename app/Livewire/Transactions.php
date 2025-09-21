<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class Transactions extends Component
{
    use WithPagination, WithoutUrlPagination;

    public function render()
    {
        $transactions = Transaction::with('category', 'card')
            ->where('user_id', auth()->id())
            ->orderBy('date', 'desc')
            ->paginate();

        return view('livewire.transactions', compact('transactions'));
    }
}
