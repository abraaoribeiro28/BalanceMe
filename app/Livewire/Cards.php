<?php

namespace App\Livewire;

use App\Models\Card;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class Cards extends Component
{
    public Collection $cards;

    #[On('load-cards')]
    public function mount(): void
    {
        $this->cards = Card::where('user_id', auth()->id())->get();
    }
}

