<div class="grid gap-4 md:gap-8 pb-8">
    <x-app.heading title="Dashboard" description="Visão geral das suas finanças">
        <flux:modal.trigger name="edit-profile">
            <flux:button variant="primary" icon="plus" class="sm:mt-0 mt-4 cursor-pointer">Nova Transação</flux:button>
        </flux:modal.trigger>
    </x-app.heading>

    <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3">
        <x-app.indicator label="Saldo Total" :value="$totalBalance" description="Atualizado em {{ $lastUpdated }}">
            <flux:icon.currency-dollar/>
        </x-app.indicator>

        <x-app.indicator label="Receitas" :value="$incomeTotal" :description="$incomeMoM" color="text-emerald-500">
            <flux:icon.arrow-trending-down class="text-emerald-500"/>
        </x-app.indicator>

        <x-app.indicator label="Despesas" :value="$expenseTotal" :description="$expenseMoM" color="text-rose-500">
            <flux:icon.arrow-trending-up class="text-rose-500"/>
        </x-app.indicator>
    </div>

    <div class="space-y-4 overflow-x-hidden">
        <flux:button.group>
            <flux:button wire:click="setTab('overview')">Visão Geral</flux:button>
            <flux:button wire:click="setTab('transactions')">Transações</flux:button>
            <flux:button wire:click="setTab('categories')">Categorias</flux:button>
            <flux:button wire:click="setTab('cards')">Cartões</flux:button>
        </flux:button.group>

        <div class="mt-2 ring-offset-background focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 space-y-4">
            @if($tab === 'overview')
                <livewire:overview/>
            @elseif($tab === 'transactions')
                <livewire:transactions/>
            @elseif($tab === 'categories')
                <livewire:categories/>
            @elseif($tab === 'cards')
                <livewire:cards/>
            @endif
        </div>
    </div>

    <livewire:modals.transaction/>
</div>
