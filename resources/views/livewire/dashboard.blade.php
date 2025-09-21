<div class="grid gap-4 md:gap-8 pb-8 px-4">
    <x-app.header title="Dashboard" description="Visão geral das suas finanças">
        <x-ui.modal.trigger id="modal-transaction">
            <x-ui.button icon="plus">Nova Transação</x-ui.button>
        </x-ui.modal.trigger>
    </x-app.header>

    <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3">
        <x-app.indicator label="Saldo Total" value="0,00" description="Atualizado em 19/09/2025">
            <x-ui.icon name="currency-dollar"/>
        </x-app.indicator>

        <x-app.indicator label="Receitas" value="0,00" description="+0% em relação ao mês anterior" color="text-emerald-500">
            <x-ui.icon name="arrow-trending-up" class="!text-emerald-500"/>
        </x-app.indicator>

        <x-app.indicator label="Despesas" value="0,00" description="+0% em relação ao mês anterior" color="text-rose-500">
            <x-ui.icon name="arrow-trending-down" class="!text-rose-500"/>
        </x-app.indicator>
    </div>

    <div class="space-y-4 overflow-x-hidden">
        <div class="inline-flex items-center rounded-md bg-[#f4f4f5] dark:bg-white/10 p-2 max-w-full overflow-x-auto">
            <x-ui.button wire:click="setTab('overview')" size="sm" variant="{{ $tab === 'overview' ? 'primary' : 'ghost' }}">Visão Geral</x-ui.button>
            <x-ui.button wire:click="setTab('transactions')" size="sm" variant="{{ $tab === 'transactions' ? 'primary' : 'ghost' }}">Transações</x-ui.button>
            <x-ui.button wire:click="setTab('categories')" size="sm" variant="{{ $tab === 'categories' ? 'primary' : 'ghost' }}">Categorias</x-ui.button>
            <x-ui.button wire:click="setTab('cards')" size="sm" variant="{{ $tab === 'cards' ? 'primary' : 'ghost' }}">Cartões</x-ui.button>
        </div>

        <div class="mt-2 ring-offset-background focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 space-y-4">
            @if($tab === 'overview')
                @include('partials.overview')
            @elseif($tab === 'transactions')
                @include('partials.transactions')
            @elseif($tab === 'categories')
                <livewire:categories/>
            @elseif($tab === 'cards')
                <livewire:cards/>
            @endif
        </div>
    </div>

    <livewire:modals.transaction/>
</div>
