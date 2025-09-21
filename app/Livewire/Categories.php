<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Categories extends Component
{
    public Collection $categories;

    public function mount(): void
    {
        $this->categories = \App\Models\Category::all();
    }
}
