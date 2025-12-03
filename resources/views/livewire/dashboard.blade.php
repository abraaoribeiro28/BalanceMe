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

    <div
        x-data="{ tab: 'overview' }"
        class="space-y-4 overflow-x-hidden"
    >
        <flux:button.group>
            <flux:button @click="tab = 'overview'">Visão Geral</flux:button>
            <flux:button @click="tab = 'transactions'">Transações</flux:button>
            <flux:button @click="tab = 'categories'">Categorias</flux:button>
            <flux:button @click="tab = 'cards'">Cartões</flux:button>
        </flux:button.group>

        <div class="mt-2 ring-offset-background focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 space-y-4">
            <div x-show="tab === 'overview'" x-cloak>
                <livewire:overview wire:key="tab-overview" />
            </div>
            <div x-show="tab === 'transactions'" x-cloak>
                <livewire:transactions wire:key="tab-transactions" />
            </div>
            <div x-show="tab === 'categories'" x-cloak>
                <livewire:categories wire:key="tab-categories" />
            </div>
            <div x-show="tab === 'cards'" x-cloak>
                <livewire:cards wire:key="tab-cards" />
            </div>
        </div>
    </div>

    <livewire:modals.transaction/>
</div>
