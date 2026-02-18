<?php

namespace App\Livewire;

use App\Livewire\Concerns\ComputesDashboardMetrics;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class Dashboard extends Component
{
    use ComputesDashboardMetrics;

    public string $totalBalance = '0,00';
    public string $incomeTotal = '0,00';
    public string $expenseTotal = '0,00';
    public string $incomeMoM = '+0,0% em relação ao mês anterior';
    public string $expenseMoM = '+0,0% em relação ao mês anterior';
    public string $lastUpdated;

    /**
     * Initialize dashboard metrics.
     */
    public function mount(): void
    {
        $this->computeMetrics();
    }

    /**
     * Recalculate and assign dashboard metrics.
     */
    #[On('transaction-saved')]
    public function computeMetrics(): void
    {
        $metrics = $this->buildDashboardMetrics(auth()->id(), Carbon::now());
        $this->applyDashboardMetrics($metrics);
    }

    /**
     * Apply computed metrics to public component properties.
     */
    private function applyDashboardMetrics(array $metrics): void
    {
        $this->lastUpdated = $metrics['lastUpdated'];
        $this->totalBalance = $metrics['totalBalance'];
        $this->incomeTotal = $metrics['incomeTotal'];
        $this->expenseTotal = $metrics['expenseTotal'];
        $this->incomeMoM = $metrics['incomeMoM'];
        $this->expenseMoM = $metrics['expenseMoM'];
    }
}
