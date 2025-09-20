<div class="rounded-lg border border-zinc-300 dark:border-white/20 shadow-2xs col-span-3">
    <div class="flex flex-col space-y-1.5 p-6">
        <h3 class="text-2xl font-semibold leading-none tracking-tight">Todas as Transações</h3>
        <p class="text-sm text-gray-500 dark:text-gray-300">Gerencie suas transações</p>
    </div>
    <div class="max-w-screen overflow-hidden">
        <div class="p-6 pt-0 overflow-auto">
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
</div>
