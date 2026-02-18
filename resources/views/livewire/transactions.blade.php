<div class="rounded-lg border border-gray-200 dark:border-transparent  bg-white dark:bg-white/5 transition-colors col-span-3">
    <div class="flex flex-col space-y-1.5 p-6">
        <h3 class="text-2xl font-semibold leading-none tracking-tight">Todas as Transações</h3>
        <p class="text-sm text-gray-500 dark:text-gray-300">Gerencie suas transações</p>
    </div>
    <div class="max-w-screen overflow-hidden">
        <div class="px-6 overflow-auto">
            <div class="space-y-8 mb-6">
                @foreach($transactions as $transaction)
                    <x-app.transaction-item
                        :label="$transaction->name"
                        :category="$transaction->category?->name ?? 'Sem categoria'"
                        :date="$transaction->date->format('d/m/Y')"
                        :value="number_format($transaction->amount, 2, ',', '.')"
                        :type="$transaction->type"
                        :card="$transaction->card?->name"
                    />
                @endforeach
            </div>
            @if($transactions->hasPages())
                <div class="pb-6">
                    {{ $transactions->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
