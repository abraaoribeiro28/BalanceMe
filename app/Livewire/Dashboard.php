<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public string $tab = 'overview';

    /**
     * Set the current active tab.
     *
     * @param string $tab
     * @return void
     */
    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }
}
