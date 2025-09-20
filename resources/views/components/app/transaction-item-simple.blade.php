@props(['label', 'category', 'date', 'type', 'value', 'card' => null])
<div class="flex items-center">
    <div @class([
            'flex h-9 w-9 items-center justify-center rounded-full',
            'bg-emerald-100' => $type === 'receita',
            'bg-rose-100' => $type != 'receita',
        ])>
        {{ $slot }}
    </div>
    <div class="ml-4 space-y-1">
        <p class="text-sm font-medium leading-none">{{ $label }}</p>
        <p class="text-sm text-gray-500 dark:text-gray-300">
            {{ $category }} •
            @if($card)
                <span style="color: rgb(138, 5, 190);">{{ $card }}</span>  •
            @endif
            {{ $date }}
        </p>
    </div>
    <div class="ml-auto flex items-center gap-2">
        <div @class([
                'font-medium',
                'text-emerald-500' => $type === 'receita' ,
                'text-rose-500' => $type != 'receita',
            ])>
            {{ $type === 'receita' ? '+' : '-' }}R$ {{ $value }}
        </div>
    </div>
</div>
