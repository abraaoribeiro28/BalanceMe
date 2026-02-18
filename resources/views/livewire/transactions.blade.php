<div class="rounded-lg border border-gray-200 dark:border-transparent bg-white dark:bg-white/5 transition-colors col-span-3">
    <div class="flex flex-col space-y-1.5 p-6">
        <h3 class="text-2xl font-semibold leading-none tracking-tight">Todas as Transações</h3>
        <p class="text-sm text-gray-500 dark:text-gray-300">Gerencie suas transações</p>
    </div>

    <div class="max-w-screen overflow-hidden">
        <div class="px-6 overflow-auto">
            <div class="space-y-8 mb-6">
                @foreach($transactions as $transaction)
                    <x-app.transaction-item
                        :transaction-id="$transaction->id"
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

    <flux:modal name="confirm-delete-transaction" class="w-[90%] max-w-lg" :dismissible="false" @close="resetDeleteTransactionState">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Excluir transação?</flux:heading>
                <flux:text class="mt-2">Esta ação não pode ser desfeita.</flux:text>
                <flux:text class="mt-1">Transação selecionada: <strong>{{ $transactionToDeleteName }}</strong></flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer/>
                <flux:button variant="ghost" wire:click="closeDeleteTransactionModal" class="cursor-pointer">
                    Cancelar
                </flux:button>
                <flux:button variant="danger" icon="trash" wire:click="deleteTransactionConfirmed" class="cursor-pointer">
                    Excluir transação
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
