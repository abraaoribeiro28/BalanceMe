@props(['label', 'type'])

<div class="rounded-lg bg-white dark:bg-white/10 border border-gray-800/15 dark:border-white/10 text-card-foreground relative">
    <div class="flex flex-col p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-x-1">
                <flux:icon.tag variant="mini" />
                <h3 class="font-medium tracking-tight text-md">{{ $label }}</h3>
            </div>
            <div class="flex items-center gap-x-1 text-sm text-gray-500 dark:text-gray-300">
                <span @class([
                    'text-rose-500' => $type === 'Despesa',
                    'text-smerald-500' => $type === 'Receita',
                    'text-blue-500' => $type === 'Ambos'
                ])>
                    {{ $type }}
                </span>
            </div>
        </div>
    </div>
</div>
