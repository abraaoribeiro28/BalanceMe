<div class="grid gap-4 lg:grid-cols-7">
    <div class="rounded-lg border border-zinc-300 dark:border-white/20 shadow-2xs lg:col-span-4">
        <div class="flex flex-col space-y-1.5 p-6">
            <h3 class="text-2xl font-semibold leading-none tracking-tight">Receitas vs Despesas</h3>
            <p class="text-sm text-gray-500 dark:text-gray-300">Comparativo dos últimos 6 meses</p>
        </div>
        <div class="p-6 pt-0 pl-2">
            {{-- Gráfico aqui... --}}
        </div>
    </div>
    <div class="rounded-lg border border-zinc-300 dark:border-white/20 shadow-2xs lg:col-span-3">
        <div class="flex flex-col space-y-1.5 p-6">
            <h3 class="text-2xl font-semibold leading-none tracking-tight">Despesas por Categoria</h3>
            <p class="text-sm text-gray-500 dark:text-gray-300">Distribuição das despesas do mês atual</p>
        </div>
        <div class="p-6 pt-0">
            @if(false)
                {{-- Gráfico aqui... --}}
            @else
                <div class="flex h-[300px] items-center justify-center">
                    <p class="text-gray-500 dark:text-gray-300">Nenhuma despesa registrada neste período</p>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="grid gap-4 lg:grid-cols-2">
    <div class="rounded-lg border border-zinc-300 dark:border-white/20 shadow-2xs">
        <div class="flex flex-col space-y-1.5 p-6">
            <h3 class="text-2xl font-semibold leading-none tracking-tight">Transações Recentes</h3>
            <p class="text-sm text-gray-500 dark:text-gray-300">Últimas 5 transações realizadas</p>
        </div>
        <div class="p-6 pt-0">
            <div class="space-y-8">
                <x-app.transaction-item-simple
                    label="Salário"
                    category="Salário"
                    date="há mais de 2 anos"
                    value="5.000,00"
                    type="receita"
                    card="Nubank"
                >
                    <x-ui.icon variant="mini" name="arrow-up" class="!text-emerald-500"/>
                </x-app.transaction-item-simple>

                <x-app.transaction-item-simple
                    label="Aluguel"
                    category="Moradia"
                    date="há mais de 2 anos"
                    value="1.200,00"
                    type="despesa"
                    card="Nubank"
                >
                    <x-ui.icon variant="mini" name="arrow-down" class="!text-rose-500"/>
                </x-app.transaction-item-simple>
            </div>
        </div>
    </div>
    <div class="rounded-lg border border-zinc-300 dark:border-white/20 shadow-2xs">
        <div class="flex flex-col space-y-1.5 p-6">
            <h3 class="text-2xl font-semibold leading-none tracking-tight">Despesas por Cartão</h3>
            <p class="text-sm text-gray-500 dark:text-gray-300">Gastos no cartão de crédito do mês atual</p>
        </div>
        <div class="p-6 pt-0">
            <div class="flex h-[300px] items-center justify-center">
                <p class="text-gray-500 dark:text-gray-300">Nenhuma despesa no cartão de crédito neste período</p>
            </div>
        </div>
    </div>
</div>
