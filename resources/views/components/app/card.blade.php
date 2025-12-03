@props(['label'])

<div class="rounded-lg bg-white dark:bg-white/10 border border-gray-800/15 dark:border-white/10 text-card-foreground relative">
    <div class="flex flex-col p-6">
        <div class="flex items-center gap-x-2">
            <flux:icon.credit-card variant="solid" />
            <h3 class="font-medium tracking-tight text-md text-nowrap text-ellipsis">{{ $label }}</h3>
        </div>
    </div>
</div>
