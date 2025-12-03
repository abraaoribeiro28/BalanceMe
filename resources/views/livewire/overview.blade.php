<div>
    <div class="grid gap-4 lg:grid-cols-7 mb-4">
        <div class="rounded-lg border border-gray-200 dark:border-transparent  bg-white dark:bg-white/5 transition-colors lg:col-span-4">
            <div class="flex flex-col space-y-1.5 p-6">
                <h3 class="text-2xl font-semibold leading-none tracking-tight">Receitas vs Despesas</h3>
                <p class="text-sm text-gray-500 dark:text-gray-300">Comparativo dos últimos 6 meses</p>
            </div>
            <div class="p-6 pt-0 pl-2">
                <div
                    x-data="window.incomeExpenseChart({
                        labels: @js($tsLabels),
                        income: @js($tsIncome),
                        expense: @js($tsExpense),
                      })"
                    x-init="init()"
                    class="w-full h-64 sm:h-72 md:h-80 lg:h-[300px]"
                >
                    <canvas id="ie-chart" class="w-full h-[300px]" wire:ignore></canvas>
                </div>
            </div>
        </div>
        <div class="rounded-lg border border-gray-200 dark:border-transparent  bg-white dark:bg-white/5 transition-colors lg:col-span-3">
            <div class="flex flex-col space-y-1.5 p-6">
                <h3 class="text-2xl font-semibold leading-none tracking-tight">Despesas por Categoria</h3>
                <p class="text-sm text-gray-500 dark:text-gray-300">Distribuição das despesas do mês atual</p>
            </div>
            <div class="p-6 pt-0">
                @if(count($catValues))
                    <div
                        x-data="window.expenseByCategoryChart({
                            labels: @js($catLabels),
                            values: @js($catValues),
                            colors: @js($catColors),
                          })"
                        x-init="init()"
                        class="w-full h-64 sm:h-72 md:h-80 lg:h-[300px]"
                    >
                        <canvas id="cat-chart" class="w-full h-full" wire:ignore></canvas>
                    </div>
                @else
                    <div class="flex h-[300px] items-center justify-center">
                        <p class="text-gray-500 dark:text-gray-300">Nenhuma despesa registrada neste período</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="grid gap-4 lg:grid-cols-2">
        <div class="rounded-lg border border-gray-200 dark:border-transparent  bg-white dark:bg-white/5 transition-colors">
            <div class="flex flex-col space-y-1.5 p-6">
                <h3 class="text-2xl font-semibold leading-none tracking-tight">Transações Recentes</h3>
                <p class="text-sm text-gray-500 dark:text-gray-300">Últimas 5 transações realizadas</p>
            </div>
            <div class="p-6 pt-0">
                <div class="space-y-8">
                    @forelse($transactions as $transaction)
                        <x-app.transaction-item-simple
                            :label="$transaction['name']"
                            :category="$transaction['category']['name']"
                            :date="date('d/m/Y'), $transaction['date']"
                            :value="number_format($transaction['amount'], 2, ',', '.')"
                            :type="$transaction['type']"
                            :card="$transaction['card']['name'] ?? null"
                        />
                    @empty
                        <div class="flex h-[300px] items-center justify-center">
                            <p class="text-gray-500 dark:text-gray-300">Nenhuma transação registrada neste período</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="rounded-lg border border-gray-200 dark:border-transparent  bg-white dark:bg-white/5 transition-colors">
            <div class="flex flex-col space-y-1.5 p-6">
                <h3 class="text-2xl font-semibold leading-none tracking-tight">Despesas por Cartão</h3>
                <p class="text-sm text-gray-500 dark:text-gray-300">Gastos no cartão de crédito do mês atual</p>
            </div>
            <div class="p-6 pt-0">
                @if(count($cardValues))
                    <div
                        x-data="window.expenseByCardChart({
                            labels: @js($cardLabels),
                            values: @js($cardValues),
                            colors: @js($cardColors),
                        })"
                        x-init="init()"
                        class="w-full h-64 sm:h-72 md:h-80 lg:h-[300px]"
                    >
                        <canvas id="card-chart" class="w-full h-full" wire:ignore></canvas>
                    </div>
                @else
                    <div class="flex h-[300px] items-center justify-center">
                        <p class="text-gray-500 dark:text-gray-300">Nenhuma despesa no cartão de crédito neste período</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script defer>
        function brl(value) {
            return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' })
                .format(value ?? 0);
        }

        /* --------- BARRAS: Receitas vs Despesas --------- */
        window.incomeExpenseChart = (initial) => ({
            chart: null,

            init() {
                const ctx = document.getElementById('ie-chart').getContext('2d');

                this.chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: initial.labels,
                        datasets: [
                            {
                                label: 'Receitas',
                                data: initial.income,
                                backgroundColor: '#10b981',
                                borderRadius: 6,
                                maxBarThickness: 40,
                            },
                            {
                                label: 'Despesas',
                                data: initial.expense,
                                backgroundColor: '#ef4444',
                                borderRadius: 6,
                                maxBarThickness: 40,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: { padding: { top: 8, right: 8, bottom: 8, left: 8 } },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: (item) => `${item.dataset.label}: ${brl(item.raw)}`
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { autoSkip: true, maxRotation: 45, minRotation: 0 }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: { callback: (v) => brl(v) }
                            }
                        }
                    }
                });

                window.addEventListener('dashboard:charts:update', (e) => {
                    const { timeseries } = e.detail;
                    if (!timeseries) return;
                    this.chart.data.labels = timeseries.labels ?? this.chart.data.labels;
                    this.chart.data.datasets[0].data = timeseries.income ?? this.chart.data.datasets[0].data;
                    this.chart.data.datasets[1].data = timeseries.expense ?? this.chart.data.datasets[1].data;
                    this.chart.update();
                });
            },
        });

        /* --------- DONUT: Despesas por Categoria --------- */
        window.expenseByCategoryChart = (initial) => ({
            chart: null,

            init() {
                const ctx = document.getElementById('cat-chart').getContext('2d');

                this.chart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: initial.labels,
                        datasets: [{
                            data: initial.values,
                            backgroundColor: initial.colors,
                            borderColor: initial.colors,
                            borderWidth: 2,
                            hoverOffset: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: { display: true, position: 'bottom' },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => `${ctx.label}: ${brl(ctx.raw)}`
                                }
                            }
                        }
                    }
                });

                window.addEventListener('dashboard:charts:update', (e) => {
                    const { categories } = e.detail;
                    if (!categories) return;
                    this.chart.data.labels = categories.labels ?? this.chart.data.labels;
                    this.chart.data.datasets[0].data = categories.values ?? this.chart.data.datasets[0].data;
                    this.chart.data.datasets[0].backgroundColor = categories.colors ?? this.chart.data.datasets[0].backgroundColor;
                    this.chart.data.datasets[0].borderColor = categories.colors ?? this.chart.data.datasets[0].borderColor;
                    this.chart.update();
                });
            },
        });

        /* --------- DONUT: Despesas por Cartão --------- */
        window.expenseByCardChart = (initial) => ({
            chart: null,

            init() {
                const ctx = document.getElementById('card-chart').getContext('2d');

                this.chart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: initial.labels,
                        datasets: [{
                            data: initial.values,
                            backgroundColor: initial.colors,
                            borderColor: initial.colors,
                            borderWidth: 2,
                            hoverOffset: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: { display: true, position: 'bottom' },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => `${ctx.label}: ${brl(ctx.raw)}`
                                }
                            }
                        }
                    }
                });

                window.addEventListener('dashboard:charts:update', (e) => {
                    const { cards } = e.detail;
                    if (!cards) return;
                    this.chart.data.labels = cards.labels ?? this.chart.data.labels;
                    this.chart.data.datasets[0].data = cards.values ?? this.chart.data.datasets[0].data;
                    this.chart.data.datasets[0].backgroundColor = cards.colors ?? this.chart.data.datasets[0].backgroundColor;
                    this.chart.data.datasets[0].borderColor = cards.colors ?? this.chart.data.datasets[0].borderColor;
                    this.chart.update();
                });
            },
        });
    </script>

</div>
