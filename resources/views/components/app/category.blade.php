@props(['label', 'type'])

<div class="rounded-lg bg-white dark:bg-white/10 border border-gray-800/15 dark:border-white/10 text-card-foreground overflow-hidden">
    <div class="p-6">
        <div class="flex items-start gap-x-2 min-w-0">
            <flux:icon.tag variant="mini" class="mt-0.5 shrink-0" />
            <div class="min-w-0">
                <h3 class="font-medium tracking-tight text-md truncate">{{ $label }}</h3>
                <p class="text-sm mt-1">
                    <span @class([
                        'text-rose-500' => $type === 'Despesa',
                        'text-emerald-500' => $type === 'Receita',
                        'text-blue-500' => $type === 'Ambos'
                    ])>
                        {{ $type }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    @isset($actions)
        <div class="px-6 pb-5 pt-3 border-t border-gray-800/10 dark:border-white/10">
            <div class="flex items-center gap-2">
                {{ $actions }}
            </div>
        </div>
    @endisset
</div>