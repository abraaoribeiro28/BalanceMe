@props(['transactionId', 'label', 'category', 'date', 'type', 'value', 'card' => null])

<div class="rounded-lg bg-white dark:bg-white/10 border border-gray-800/15 dark:border-white/10 text-card-foreground overflow-hidden">
    <div class="p-5 flex sm:flex-row flex-col sm:items-center sm:justify-between gap-3">
        <div class="flex items-start gap-3 min-w-0">
            <div @class([
                'flex min-h-9 min-w-9 items-center justify-center rounded-full shrink-0',
                'bg-emerald-100' => $type === 'Receita',
                'bg-rose-100' => $type !== 'Receita',
            ])>
                @if($type === 'Receita')
                    <flux:icon.arrow-up variant="micro" class="!text-emerald-500"/>
                @else
                    <flux:icon.arrow-down variant="micro" class="!text-rose-500"/>
                @endif
            </div>

            <div class="space-y-1 min-w-0">
                <p class="text-sm font-medium leading-none truncate">{{ $label }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-300 truncate">
                    {{ $category }} &bull;
                    @if($card)
                        <span style="color: rgb(138, 5, 190);">{{ $card }}</span> &bull;
                    @endif
                    {{ $date }}
                </p>
            </div>
        </div>

        <div @class([
            'font-medium whitespace-nowrap sm:ml-3',
            'text-emerald-500' => $type === 'Receita',
            'text-rose-500' => $type !== 'Receita',
        ])>
            {{ $type === 'Receita' ? '+' : '-' }}R$ {{ $value }}
        </div>
    </div>

    <div class="px-5 pb-4 pt-3 border-t border-gray-800/10 dark:border-white/10">
        <div class="flex items-center gap-2 sm:justify-end">
            <flux:button
                variant="filled"
                size="sm"
                icon="pencil-square"
                wire:click="editTransaction({{ $transactionId }})"
                class="cursor-pointer"
            >
                Editar
            </flux:button>

            <flux:button
                variant="danger"
                size="sm"
                icon="trash"
                wire:click="confirmDeleteTransaction({{ $transactionId }})"
                class="cursor-pointer"
            >
                Excluir
            </flux:button>
        </div>
    </div>
</div>