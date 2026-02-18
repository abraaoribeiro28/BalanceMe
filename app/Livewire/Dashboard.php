<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Attributes\On;

class Dashboard extends Component
{


    public string $totalBalance = '0,00';
    public string $incomeTotal = '0,00';
    public string $expenseTotal = '0,00';
    public string $incomeMoM = '+0,0% em relação ao mês anterior';
    public string $expenseMoM = '+0,0% em relação ao mês anterior';
    public string $lastUpdated;

    /***
     * Initializes the dashboard metrics and sets the last updated date.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->lastUpdated = Carbon::now()->format('d/m/Y');
        $this->computeMetrics();
    }

    /**
     * Compute and assign financial metrics for the dashboard.
     *
     * This includes:
     * - All-time totals for income, expenses, and balance.
     * - Current month totals for income and expenses.
     * - Previous month totals for income and expenses.
     * - Month-over-month percentage changes for income and expenses.
     *
     * Formatted values are stored in component properties for rendering.
     *
     * @return void
     */
    #[On('transaction-saved')]
    public function computeMetrics(): void
    {
        $userId = auth()->id();

        $now = Carbon::now();
        $currentStart = $now->copy()->startOfMonth();
        $currentEnd = $now->copy()->endOfMonth();
        $prevStart = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $prevEnd = $now->copy()->subMonthNoOverflow()->endOfMonth();

        $aggregates = Transaction::query()
            ->where('user_id', $userId)
            ->selectRaw("
                COALESCE(SUM(CASE WHEN type = 'Receita' THEN amount ELSE 0 END), 0) as income_all,
                COALESCE(SUM(CASE WHEN type = 'Despesa' THEN amount ELSE 0 END), 0) as expense_all,
                COALESCE(SUM(CASE
                    WHEN type = 'Receita' AND date BETWEEN ? AND ? THEN amount
                    ELSE 0
                END), 0) as income_current,
                COALESCE(SUM(CASE
                    WHEN type = 'Receita' AND date BETWEEN ? AND ? THEN amount
                    ELSE 0
                END), 0) as income_prev,
                COALESCE(SUM(CASE
                    WHEN type = 'Despesa' AND date BETWEEN ? AND ? THEN amount
                    ELSE 0
                END), 0) as expense_current,
                COALESCE(SUM(CASE
                    WHEN type = 'Despesa' AND date BETWEEN ? AND ? THEN amount
                    ELSE 0
                END), 0) as expense_prev
            ", [
                $currentStart->toDateString(),
                $currentEnd->toDateString(),
                $prevStart->toDateString(),
                $prevEnd->toDateString(),
                $currentStart->toDateString(),
                $currentEnd->toDateString(),
                $prevStart->toDateString(),
                $prevEnd->toDateString(),
            ])
            ->first();

        $incomeAll = (float) ($aggregates?->income_all ?? 0);
        $expenseAll = (float) ($aggregates?->expense_all ?? 0);
        $incomeCurrent = (float) ($aggregates?->income_current ?? 0);
        $incomePrev = (float) ($aggregates?->income_prev ?? 0);
        $expenseCurrent = (float) ($aggregates?->expense_current ?? 0);
        $expensePrev = (float) ($aggregates?->expense_prev ?? 0);

        $balance = $incomeAll - $expenseAll;

        $incomePct = $this->percentChange($incomePrev, $incomeCurrent);
        $expensePct = $this->percentChange($expensePrev, $expenseCurrent);

        // Assign formatted strings
        $this->lastUpdated = $now->format('d/m/Y');
        $this->totalBalance = $this->fmtCurrency($balance);
        $this->incomeTotal = $this->fmtCurrency($incomeCurrent);
        $this->expenseTotal = $this->fmtCurrency($expenseCurrent);
        $this->incomeMoM = $this->fmtPercentDesc($incomePct);
        $this->expenseMoM = $this->fmtPercentDesc($expensePct);
    }

    /**
     * Format a float value as a currency string.
     *
     * @param float $value
     * @return string
     */
    private function fmtCurrency(float $value): string
    {
        return number_format($value, 2, ',', '.');
    }

    /**
     * Calculate the percentage change between two values.
     *
     * @param float $previous
     * @param float $current
     * @return float
     */
    private function percentChange(float $previous, float $current): float
    {
        if ($previous <= 0.0) {
            return $current > 0.0 ? 100.0 : 0.0;
        }

        return (($current - $previous) / $previous) * 100.0;
    }

    /**
     * Format a percentage value into a descriptive string.
     *
     * @param  float  $pct
     * @return string
     */
    private function fmtPercentDesc(float $pct): string
    {
        $sign = $pct >= 0 ? '+' : '';
        return $sign . number_format($pct, 1, ',', '.') . '% em relação ao mês anterior';
    }
}
