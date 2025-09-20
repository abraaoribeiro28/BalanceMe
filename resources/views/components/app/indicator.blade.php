@props(['label', 'value', 'description', 'color' => null])

<div class="rounded-lg border border-zinc-300 dark:border-white/20 shadow-2xs">
    <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
        <h3 class="tracking-tight text-sm font-medium">{{ $label }}</h3>
        {{ $slot }}
    </div>
    <div class="p-6 pt-0">
        <div class="text-2xl font-bold {{ $color }}">R$ {{ $value }}</div>
        <p class="text-xs text-gray-500">{{ $description }}</p>
    </div>
</div>
