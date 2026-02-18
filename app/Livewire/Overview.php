<?php

namespace App\Livewire;

use App\Livewire\Concerns\BuildsOverviewDatasets;
use Exception;
use Livewire\Attributes\On;
use Livewire\Component;

class Overview extends Component
{
    use BuildsOverviewDatasets;

    public array $tsLabels = [];
    public array $tsIncome = [];
    public array $tsExpense = [];
    public string $timeSeriesChartKey = 'ts-initial';

    public array $catLabels = [];
    public array $catValues = [];
    public array $catColors = [];
    public string $categoryChartKey = 'cat-initial';

    public array $cardLabels = [];
    public array $cardValues = [];
    public array $cardColors = [];
    public string $cardChartKey = 'card-initial';

    public array $transactions = [];

    /**
     * Initialize overview datasets.
     */
    public function mount(): void
    {
        $this->refreshDashboardCharts();
    }

    /**
     * Recompute chart datasets and recent transactions.
     *
     * @throws Exception
     */
    #[On('transaction-saved')]
    public function refreshDashboardCharts(): void
    {
        $datasets = $this->buildOverviewDatasets(auth()->id());
        $this->applyOverviewDatasets($datasets);
        $this->refreshChartKeys();
    }

    /**
     * Apply the computed datasets to component properties.
     */
    private function applyOverviewDatasets(array $datasets): void
    {
        $this->tsLabels = $datasets['tsLabels'];
        $this->tsIncome = $datasets['tsIncome'];
        $this->tsExpense = $datasets['tsExpense'];

        $this->catLabels = $datasets['catLabels'];
        $this->catValues = $datasets['catValues'];
        $this->catColors = $datasets['catColors'];

        $this->cardLabels = $datasets['cardLabels'];
        $this->cardValues = $datasets['cardValues'];
        $this->cardColors = $datasets['cardColors'];

        $this->transactions = $datasets['transactions'];
    }

    /**
     * Refresh Livewire keys to force chart re-rendering.
     */
    private function refreshChartKeys(): void
    {
        $this->timeSeriesChartKey = 'ts-' . md5($this->toChartHash([
            $this->tsLabels,
            $this->tsIncome,
            $this->tsExpense,
        ]));

        $this->categoryChartKey = 'cat-' . md5($this->toChartHash([
            $this->catLabels,
            $this->catValues,
            $this->catColors,
        ]));

        $this->cardChartKey = 'card-' . md5($this->toChartHash([
            $this->cardLabels,
            $this->cardValues,
            $this->cardColors,
        ]));
    }

    /**
     * Serialize chart payload for deterministic hashing.
     */
    private function toChartHash(array $payload): string
    {
        return json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE) ?: '';
    }
}
