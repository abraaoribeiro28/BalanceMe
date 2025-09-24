<?php

namespace App\Livewire;

use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Overview extends Component
{
    // Timeseries (barras)
    public array $tsLabels = [];
    public array $tsIncome = [];
    public array $tsExpense = [];

    // Donut por categoria
    public array $catLabels = [];
    public array $catValues = [];
    public array $catColors = [];

    // Donut por cartão
    public array $cardLabels = [];
    public array $cardValues = [];
    public array $cardColors = [];

    public array $transactions = [];

    /**
     * initialize dashboard charts with initial data.
     */
    public function mount(): void
    {
        // Barras (6 meses)
        [$this->tsLabels, $this->tsIncome, $this->tsExpense] = $this->buildTimeSeries();

        // Donut por categoria (mês atual)
        [$this->catLabels, $this->catValues, $this->catColors] = $this->buildCategoryDonut();

        // Donut por cartão (mês atual)
        [$this->cardLabels, $this->cardValues, $this->cardColors] = $this->buildCardDonut();

        $this->transactions = Transaction::with('category', 'card')
            ->where('user_id', auth()->id())
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    /**
     * Recompute data and dispatch a single event to update all charts.
     */
    public function refreshDashboardCharts(): void
    {
        [$tsL, $tsI, $tsE] = $this->buildTimeSeries();
        [$cL, $cV, $cC]    = $this->buildCategoryDonut();
        [$cdL, $cdV, $cdC] = $this->buildCardDonut();

        $this->dispatch('dashboard:charts:update',
            timeseries: ['labels' => $tsL, 'income' => $tsI, 'expense' => $tsE],
            categories: ['labels' => $cL, 'values' => $cV, 'colors' => $cC],
            cards:      ['labels' => $cdL, 'values' => $cdV, 'colors' => $cdC],
        );
    }

    /**
     * Aggregate income/expense by month for the last 6 months.
     *
     * @return array{0:list<string>,1:list<float>,2:list<float>} [labels, income, expense]
     */
    private function buildTimeSeries(): array
    {
        $start = now()->startOfMonth()->subMonths(5);
        $end   = now()->endOfMonth();
        $pt = [1=>'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];

        $labels  = [];
        $income  = [];
        $expense = [];

        for ($i=0; $i<6; $i++) {
            $m = $start->copy()->addMonths($i);
            $key = $m->format('Y-m');
            $labels[$key]  = $pt[(int)$m->format('n')];
            $income[$key]  = 0.0;
            $expense[$key] = 0.0;
        }

        $rows = Transaction::selectRaw("
                YEAR(date) y, MONTH(date) m,
                SUM(CASE WHEN type='Receita' THEN amount ELSE 0 END) income,
                SUM(CASE WHEN type='Despesa' THEN amount ELSE 0 END) expense
            ")
            ->where('user_id', auth()->id())
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->groupByRaw('YEAR(date), MONTH(date)')
            ->orderByRaw('y, m')
            ->get();

        foreach ($rows as $r) {
            $key = sprintf('%04d-%02d', $r->y, $r->m);
            if (isset($income[$key])) {
                $income[$key]  = (float)$r->income;
                $expense[$key] = (float)$r->expense;
            }
        }

        return [array_values($labels), array_values($income), array_values($expense)];
    }

    /**
     * Sum current-month expenses grouped by category and build distinct colors.
     *
     * @return array{0:list<string>,1:list<float>,2:list<string>} [labels, values, colorsHex]
     */
    private function buildCategoryDonut(): array
    {
        $start = now()->startOfMonth()->toDateString();
        $end   = now()->endOfMonth()->toDateString();

        $rows = Transaction::query()
            ->join('categories', 'categories.id', '=', 'transactions.category_id')
            ->where('transactions.user_id', auth()->id())
            ->where('transactions.type', 'Despesa')
            ->whereBetween('transactions.date', [$start, $end])
            ->groupBy('categories.id', 'categories.name')
            ->orderByRaw('SUM(transactions.amount) DESC')
            ->get([
                DB::raw('categories.id    AS id'),
                DB::raw('categories.name  AS label'),
                DB::raw('SUM(transactions.amount) AS total'),
            ]);

        $labels = $rows->pluck('label')->all();
        $values = $rows->pluck('total')->map(fn($v) => (float) $v)->all();
        $colors = $this->generateDistinctColors(count($labels));

        return [$labels, $values, $colors];
    }

    /**
     * Sum current-month expenses grouped by card and build distinct colors.
     *
     * Observação: filtra por transações com card_id não nulo (cartões de crédito/débito que você gravar na transação).
     * Se você tiver um campo específico para "crédito", ajuste o where() conforme necessário.
     *
     * @return array{0:list<string>,1:list<float>,2:list<string>} [labels, values, colorsHex]
     */
    private function buildCardDonut(): array
    {
        $start = now()->startOfMonth()->toDateString();
        $end   = now()->endOfMonth()->toDateString();

        $rows = Transaction::query()
            ->join('cards', 'cards.id', '=', 'transactions.card_id')
            ->where('transactions.user_id', auth()->id())
            ->where('transactions.type', 'Despesa')
            ->whereNotNull('transactions.card_id')
            ->whereBetween('transactions.date', [$start, $end])
            ->groupBy('cards.id', 'cards.name')
            ->orderByRaw('SUM(transactions.amount) DESC')
            ->get([
                DB::raw('cards.id    AS id'),
                DB::raw('cards.name  AS label'),
                DB::raw('SUM(transactions.amount) AS total'),
            ]);

        $labels = $rows->pluck('label')->all();
        $values = $rows->pluck('total')->map(fn($v) => (float)$v)->all();
        $colors = $this->generateDistinctColors(count($labels));

        return [$labels, $values, $colors];
    }

    /**
     * Generate N visually distinct colors using HSL with a random hue offset.
     *
     * @return list<string> HEX colors in the form "#rrggbb"
     * @throws Exception
     */
    private function generateDistinctColors(int $number, float $saturation = 0.70, float $lightness = 0.52): array
    {
        if ($number <= 0) {
            return [];
        }

        $offset = random_int(0, 359);
        $step = 360 / $number;

        $colors = [];
        for ($i = 0; $i < $number; $i++) {
            $h = fmod($offset + $i * $step, 360.0);
            $colors[] = $this->hslToHex((int) round($h), $saturation, $lightness);
        }

        return $colors;
    }

    private function hslToHex(int $hue, float $saturation, float $lightness): string
    {
        $saturation = max(0, min(1, $saturation));
        $lightness = max(0, min(1, $lightness));

        $c = (1 - abs(2 * $lightness - 1)) * $saturation;
        $x = $c * (1 - abs(fmod($hue / 60, 2) - 1));
        $m = $lightness - $c / 2;

        $r1 = $g1 = $b1 = 0;
        if ($hue < 60)       { $r1 = $c; $g1 = $x; $b1 = 0; }
        elseif ($hue < 120)  { $r1 = $x; $g1 = $c; $b1 = 0; }
        elseif ($hue < 180)  { $r1 = 0;  $g1 = $c; $b1 = $x; }
        elseif ($hue < 240)  { $r1 = 0;  $g1 = $x; $b1 = $c; }
        elseif ($hue < 300)  { $r1 = $x; $g1 = 0;  $b1 = $c; }
        else                 { $r1 = $c; $g1 = 0;  $b1 = $x; }

        $r = (int) round(($r1 + $m) * 255);
        $g = (int) round(($g1 + $m) * 255);
        $b = (int) round(($b1 + $m) * 255);

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
}
