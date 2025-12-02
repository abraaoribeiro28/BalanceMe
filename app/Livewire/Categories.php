<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class Categories extends Component
{
    public Collection $categories;

    #[On('load-categories')]
    public function mount(): void
    {
        $this->categories = Category::where('user_id', auth()->id())->get();
    }
}
