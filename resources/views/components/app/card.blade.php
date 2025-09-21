@props(['label', 'color'])

<div class="rounded-lg border border-zinc-300 dark:border-white/20 text-card-foreground shadow-2xs relative">
    <div class="flex flex-col space-y-1.5 p-6 pb-2">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h3 class="font-semibold tracking-tight text-lg">{{ $label }}</h3>
            </div>
            <div class="flex gap-1">
                <x-ui.button size="xs" variant="soft" class="!p-0">
                    <x-ui.icon variant="mini" name="pencil-square"/>
                </x-ui.button>
                <x-ui.button size="xs" variant="soft" class="!p-0">
                    <x-ui.icon variant="mini" name="trash" class="!text-rose-500"/>
                </x-ui.button>
            </div>
        </div>
    </div>
    <div class="p-6 pt-0">
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-300">
            <x-ui.icon name="credit-card"/>
            <span>Cartão de Crédito</span>
        </div>
    </div>
</div>
