@props(['label', 'category', 'date', 'type', 'value', 'card' => null])
<div class="flex items-center">
        <div @class([
                'flex h-9 w-9 items-center justify-center rounded-full',
                'bg-emerald-100' => $type === 'Receita',
                'bg-rose-100' => $type != 'Receita',
            ])>
            @if($type === 'Receita')
                <x-ui.icon variant="mini" name="arrow-up" class="!text-emerald-500"/>
            @else
                <x-ui.icon variant="mini" name="arrow-down" class="!text-rose-500"/>
            @endif
        </div>
    <div class="flex sm:flex-row flex-col sm:justify-between sm:w-full sm:items-center ml-4">
        <div class="space-y-1">
            <p class="text-sm font-medium leading-none">{{ $label }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-300">
                {{ $category }} •
                @if($card)
                    <span style="color: rgb(138, 5, 190);">{{ $card }}</span>  •
                @endif
                {{ $date }}
            </p>
        </div>

    <div class="flex items-center gap-2">
        <div @class([
                'font-medium',
                'text-emerald-500' => $type === 'Receita' ,
                'text-rose-500' => $type != 'Receita',
            ])>
            {{ $type === 'Receita' ? '+' : '-' }}R$ {{ $value }}
        </div>
    </div>
    </div>
</div>
