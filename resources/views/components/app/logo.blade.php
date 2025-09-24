<div class="flex items-center gap-2">
    <a data-slot="button"
       class="text-primary-brand bg-base-200/6 relative inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-[3px] text-sm font-medium"
       href="/">
        <img src="{{ asset('images/logo.webp') }}" alt="Logo">
    </a>
    <a class="inline-flex items-center"
       href="{{ route('home') }}"
       wire:navigate>
        <h1 class='text-primary-brand text-lg font-bold leading-8'>BalanceMe</h1>
    </a>
</div>
