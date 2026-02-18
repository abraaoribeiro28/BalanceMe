<?php

namespace App\Livewire\Concerns;

use App\Models\Transaction;
use Carbon\Carbon;

trait ComputesDashboardMetrics
{
    private const DASHBOARD_TYPE_INCOME = 'Receita';
    private const DASHBOARD_TYPE_EXPENSE = 'Despesa';

    /**
     * Build formatted dashboard metrics for the given user.
     *
     * @return array{
     *     lastUpdated: string,
     *     totalBalance: string,
     *     incomeTotal: string,
     *     expenseTotal: string,
     *     incomeMoM: string,
     *     expenseMoM: string
     * }
     */
    private function buildDashboardMetrics(?int $userId, Carbon $reference): array
    {
        $period = $this->dashboardMonthlyPeriod($reference);
        $aggregates = $this->dashboardAggregates($userId, $period);

        $incomeAll = $this->dashboardAggregateValue($aggregates, 'income_all');
        $expenseAll = $this->dashboardAggregateValue($aggregates, 'expense_all');
        $incomeCurrent = $this->dashboardAggregateValue($aggregates, 'income_current');
        $incomePrevious = $this->dashboardAggregateValue($aggregates, 'income_prev');
        $expenseCurrent = $this->dashboardAggregateValue($aggregates, 'expense_current');
        $expensePrevious = $this->dashboardAggregateValue($aggregates, 'expense_prev');

        $balance = $incomeAll - $expenseAll;

        return [
            'lastUpdated' => $reference->format('d/m/Y'),
            'totalBalance' => $this->dashboardCurrency($balance),
            'incomeTotal' => $this->dashboardCurrency($incomeCurrent),
            'expenseTotal' => $this->dashboardCurrency($expenseCurrent),
            'incomeMoM' => $this->dashboardPercentDescription(
                $this->dashboardPercentChange($incomePrevious, $incomeCurrent)
            ),
            'expenseMoM' => $this->dashboardPercentDescription(
                $this->dashboardPercentChange($expensePrevious, $expenseCurrent)
            ),
        ];
    }

    /**
     * Resolve current and previous month boundaries.
     *
     * @return array{
     *     current_start: string,
     *     current_end: string,
     *     previous_start: string,
     *     previous_end: string
     * }
     */
    private function dashboardMonthlyPeriod(Carbon $reference): array
    {
        return [
            'current_start' => $reference->copy()->startOfMonth()->toDateString(),
            'current_end' => $reference->copy()->endOfMonth()->toDateString(),
            'previous_start' => $reference->copy()->subMonthNoOverflow()->startOfMonth()->toDateString(),
            'previous_end' => $reference->copy()->subMonthNoOverflow()->endOfMonth()->toDateString(),
        ];
    }

    /**
     * Run the dashboard aggregate query.
     *
     * @param array{
     *     current_start: string,
     *     current_end: string,
     *     previous_start: string,
     *     previous_end: string
     * } $period
     */
    private function dashboardAggregates(?int $userId, array $period): object
    {
        $aggregates = Transaction::query()
            ->where('user_id', $userId)
            ->selectRaw($this->dashboardAggregateSql(), $this->dashboardAggregateBindings($period))
            ->first();

        return $aggregates ?? (object) [];
    }

    /**
     * Build SQL used to aggregate dashboard metrics.
     */
    private function dashboardAggregateSql(): string
    {
        return "
            COALESCE(SUM(CASE WHEN type = '" . self::DASHBOARD_TYPE_INCOME . "' THEN amount ELSE 0 END), 0) AS income_all,
            COALESCE(SUM(CASE WHEN type = '" . self::DASHBOARD_TYPE_EXPENSE . "' THEN amount ELSE 0 END), 0) AS expense_all,
            COALESCE(SUM(CASE WHEN type = '" . self::DASHBOARD_TYPE_INCOME . "' AND date BETWEEN ? AND ? THEN amount ELSE 0 END), 0) AS income_current,
            COALESCE(SUM(CASE WHEN type = '" . self::DASHBOARD_TYPE_INCOME . "' AND date BETWEEN ? AND ? THEN amount ELSE 0 END), 0) AS income_prev,
            COALESCE(SUM(CASE WHEN type = '" . self::DASHBOARD_TYPE_EXPENSE . "' AND date BETWEEN ? AND ? THEN amount ELSE 0 END), 0) AS expense_current,
            COALESCE(SUM(CASE WHEN type = '" . self::DASHBOARD_TYPE_EXPENSE . "' AND date BETWEEN ? AND ? THEN amount ELSE 0 END), 0) AS expense_prev
        ";
    }

    /**
     * Build bindings for the aggregate SQL.
     *
     * @param array{
     *     current_start: string,
     *     current_end: string,
     *     previous_start: string,
     *     previous_end: string
     * } $period
     * @return list<string>
     */
    private function dashboardAggregateBindings(array $period): array
    {
        return [
            $period['current_start'],
            $period['current_end'],
            $period['previous_start'],
            $period['previous_end'],
            $period['current_start'],
            $period['current_end'],
            $period['previous_start'],
            $period['previous_end'],
        ];
    }

    /**
     * Get an aggregate field as float.
     */
    private function dashboardAggregateValue(object $aggregates, string $field): float
    {
        return (float) ($aggregates->{$field} ?? 0.0);
    }

    /**
     * Format a value as BRL-like currency.
     */
    private function dashboardCurrency(float $value): string
    {
        return number_format($value, 2, ',', '.');
    }

    /**
     * Calculate percentage change from previous to current.
     */
    private function dashboardPercentChange(float $previous, float $current): float
    {
        if ($previous <= 0.0) {
            return $current > 0.0 ? 100.0 : 0.0;
        }

        return (($current - $previous) / $previous) * 100.0;
    }

    /**
     * Format the month-over-month percentage label.
     */
    private function dashboardPercentDescription(float $percentage): string
    {
        $sign = $percentage >= 0 ? '+' : '';

        return $sign . number_format($percentage, 1, ',', '.') . '% em relação ao mês anterior';
    }
}
