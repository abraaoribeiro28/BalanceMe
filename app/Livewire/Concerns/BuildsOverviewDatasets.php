<?php

namespace App\Livewire\Concerns;

use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait BuildsOverviewDatasets
{
    private const OVERVIEW_TYPE_INCOME = 'Receita';
    private const OVERVIEW_TYPE_EXPENSE = 'Despesa';
    private const OVERVIEW_MONTH_LABELS = [
        1 => 'Jan',
        2 => 'Fev',
        3 => 'Mar',
        4 => 'Abr',
        5 => 'Mai',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Ago',
        9 => 'Set',
        10 => 'Out',
        11 => 'Nov',
        12 => 'Dez',
    ];

    /**
     * Build all datasets required by the overview tab.
     *
     * @return array{
     *     tsLabels: list<string>,
     *     tsIncome: list<float>,
     *     tsExpense: list<float>,
     *     catLabels: list<string>,
     *     catValues: list<float>,
     *     catColors: list<string>,
     *     cardLabels: list<string>,
     *     cardValues: list<float>,
     *     cardColors: list<string>,
     *     transactions: array<int, array<string, mixed>>
     * }
     *
     * @throws Exception
     */
    private function buildOverviewDatasets(?int $userId): array
    {
        [$tsLabels, $tsIncome, $tsExpense] = $this->overviewTimeSeries($userId);
        [$catLabels, $catValues, $catColors] = $this->overviewCategoryDonut($userId);
        [$cardLabels, $cardValues, $cardColors] = $this->overviewCardDonut($userId);

        return [
            'tsLabels' => $tsLabels,
            'tsIncome' => $tsIncome,
            'tsExpense' => $tsExpense,
            'catLabels' => $catLabels,
            'catValues' => $catValues,
            'catColors' => $catColors,
            'cardLabels' => $cardLabels,
            'cardValues' => $cardValues,
            'cardColors' => $cardColors,
            'transactions' => $this->overviewRecentTransactions($userId),
        ];
    }

    /**
     * Fetch five most recent transactions with relationships.
     *
     * @return array<int, array<string, mixed>>
     */
    private function overviewRecentTransactions(?int $userId): array
    {
        return Transaction::with('category', 'card')
            ->where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    /**
     * Build income and expense totals for the last six months.
     *
     * @return array{0:list<string>,1:list<float>,2:list<float>}
     */
    private function overviewTimeSeries(?int $userId): array
    {
        [$start, $end] = $this->overviewTimeSeriesRange();
        [$labels, $income, $expense] = $this->overviewSeriesBuckets($start, 6);

        $rows = Transaction::query()
            ->select(['date', 'type', 'amount'])
            ->where('user_id', $userId)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date')
            ->get();

        foreach ($rows as $row) {
            $this->overviewAccumulateSeries($row, $income, $expense);
        }

        return [array_values($labels), array_values($income), array_values($expense)];
    }

    /**
     * Resolve the date range used in the time series chart.
     *
     * @return array{0:Carbon,1:Carbon}
     */
    private function overviewTimeSeriesRange(): array
    {
        return [
            now()->startOfMonth()->subMonths(5),
            now()->endOfMonth(),
        ];
    }

    /**
     * Initialize month buckets used by the time series.
     *
     * @return array{
     *     0:array<string, string>,
     *     1:array<string, float>,
     *     2:array<string, float>
     * }
     */
    private function overviewSeriesBuckets(Carbon $start, int $months): array
    {
        $labels = [];
        $income = [];
        $expense = [];

        for ($index = 0; $index < $months; $index++) {
            $month = $start->copy()->addMonths($index);
            $key = $month->format('Y-m');

            $labels[$key] = self::OVERVIEW_MONTH_LABELS[(int) $month->format('n')] ?? '';
            $income[$key] = 0.0;
            $expense[$key] = 0.0;
        }

        return [$labels, $income, $expense];
    }

    /**
     * Accumulate one transaction value into the proper month bucket.
     *
     * @param array<string, float> $income
     * @param array<string, float> $expense
     */
    private function overviewAccumulateSeries(Transaction $transaction, array &$income, array &$expense): void
    {
        $key = $transaction->date->format('Y-m');

        if (! isset($income[$key])) {
            return;
        }

        if ($transaction->type === self::OVERVIEW_TYPE_INCOME) {
            $income[$key] += (float) $transaction->amount;
            return;
        }

        if ($transaction->type === self::OVERVIEW_TYPE_EXPENSE) {
            $expense[$key] += (float) $transaction->amount;
        }
    }

    /**
     * Build the category donut dataset for current-month expenses.
     *
     * @return array{0:list<string>,1:list<float>,2:list<string>}
     *
     * @throws Exception
     */
    private function overviewCategoryDonut(?int $userId): array
    {
        return $this->overviewExpenseDonut($userId, 'categories', 'category_id', 'name');
    }

    /**
     * Build the card donut dataset for current-month expenses.
     *
     * @return array{0:list<string>,1:list<float>,2:list<string>}
     *
     * @throws Exception
     */
    private function overviewCardDonut(?int $userId): array
    {
        return $this->overviewExpenseDonut($userId, 'cards', 'card_id', 'name', true);
    }

    /**
     * Build grouped expense data used by donut charts.
     *
     * @return array{0:list<string>,1:list<float>,2:list<string>}
     *
     * @throws Exception
     */
    private function overviewExpenseDonut(
        ?int $userId,
        string $table,
        string $foreignKey,
        string $labelColumn,
        bool $requireForeignKey = false
    ): array {
        [$startDate, $endDate] = $this->overviewCurrentMonthRange();

        $foreignColumn = "transactions.{$foreignKey}";
        $labelField = "{$table}.{$labelColumn}";

        $query = Transaction::query()
            ->join($table, "{$table}.id", '=', $foreignColumn)
            ->where('transactions.user_id', $userId)
            ->where('transactions.type', self::OVERVIEW_TYPE_EXPENSE)
            ->whereBetween('transactions.date', [$startDate, $endDate]);

        if ($requireForeignKey) {
            $query->whereNotNull($foreignColumn);
        }

        $rows = $query
            ->groupBy("{$table}.id", $labelField)
            ->orderByRaw('SUM(transactions.amount) DESC')
            ->get([
                DB::raw("{$table}.id AS id"),
                DB::raw("{$labelField} AS label"),
                DB::raw('SUM(transactions.amount) AS total'),
            ]);

        return $this->overviewFormatDonutRows($rows);
    }

    /**
     * Resolve current-month start and end date strings.
     *
     * @return array{0:string,1:string}
     */
    private function overviewCurrentMonthRange(): array
    {
        return [
            now()->startOfMonth()->toDateString(),
            now()->endOfMonth()->toDateString(),
        ];
    }

    /**
     * Convert grouped rows to donut chart arrays.
     *
     * @return array{0:list<string>,1:list<float>,2:list<string>}
     *
     * @throws Exception
     */
    private function overviewFormatDonutRows(Collection $rows): array
    {
        $labels = $rows->pluck('label')->map(static fn (mixed $value) => (string) $value)->all();
        $values = $rows->pluck('total')->map(static fn (mixed $value) => (float) $value)->all();
        $colors = $this->overviewDistinctColors(count($labels));

        return [$labels, $values, $colors];
    }

    /**
     * Generate visually distinct HEX colors.
     *
     * @return list<string>
     *
     * @throws Exception
     */
    private function overviewDistinctColors(int $count, float $saturation = 0.70, float $lightness = 0.52): array
    {
        if ($count <= 0) {
            return [];
        }

        $offset = random_int(0, 359);
        $step = 360 / $count;

        $colors = [];
        for ($index = 0; $index < $count; $index++) {
            $hue = fmod($offset + $index * $step, 360.0);
            $colors[] = $this->overviewHslToHex((int) round($hue), $saturation, $lightness);
        }

        return $colors;
    }

    /**
     * Convert an HSL color to HEX.
     */
    private function overviewHslToHex(int $hue, float $saturation, float $lightness): string
    {
        $saturation = max(0, min(1, $saturation));
        $lightness = max(0, min(1, $lightness));

        $c = (1 - abs(2 * $lightness - 1)) * $saturation;
        $x = $c * (1 - abs(fmod($hue / 60, 2) - 1));
        $m = $lightness - $c / 2;

        $r1 = $g1 = $b1 = 0;
        if ($hue < 60) {
            $r1 = $c;
            $g1 = $x;
            $b1 = 0;
        } elseif ($hue < 120) {
            $r1 = $x;
            $g1 = $c;
            $b1 = 0;
        } elseif ($hue < 180) {
            $r1 = 0;
            $g1 = $c;
            $b1 = $x;
        } elseif ($hue < 240) {
            $r1 = 0;
            $g1 = $x;
            $b1 = $c;
        } elseif ($hue < 300) {
            $r1 = $x;
            $g1 = 0;
            $b1 = $c;
        } else {
            $r1 = $c;
            $g1 = 0;
            $b1 = $x;
        }

        $red = (int) round(($r1 + $m) * 255);
        $green = (int) round(($g1 + $m) * 255);
        $blue = (int) round(($b1 + $m) * 255);

        return sprintf('#%02x%02x%02x', $red, $green, $blue);
    }
}
