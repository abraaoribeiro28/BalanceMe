@props(['label', 'type'])

<div class="rounded-lg border border-zinc-300 dark:border-white/20 text-card-foreground shadow-2xs relative">
    <div class="flex flex-col space-y-1.5 p-6 pb-2">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h3 class="font-semibold tracking-tight text-lg">{{ $label }}</h3>
            </div>
{{--            <div class="flex gap-1">--}}
{{--                <x-ui.button size="xs" variant="soft" class="!p-0">--}}
{{--                    <x-ui.icon variant="mini" name="pencil-square"/>--}}
{{--                </x-ui.button>--}}
{{--                <x-ui.button size="xs" variant="soft" class="!p-0">--}}
{{--                    <x-ui.icon variant="mini" name="trash" class="!text-rose-500"/>--}}
{{--                </x-ui.button>--}}
{{--            </div>--}}
        </div>
    </div>
    <div class="p-6 pt-0">
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-300">
            <flux:icon.tag variant="mini" />
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
