<div class="grid gap-4 md:gap-8 pb-8 px-4">
    <x-app.header title="Dashboard" description="Visão geral das suas finanças">
        <x-ui.modal.trigger id="transactionModal">
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
            @php $tab = 'overview' @endphp
            <x-ui.button size="sm" variant="{{ $tab === 'overview' ? 'primary' : 'ghost' }}">Visão Geral</x-ui.button>
            <x-ui.button size="sm" variant="{{ $tab === 'transactions' ? 'primary' : 'ghost' }}">Transações</x-ui.button>
            <x-ui.button size="sm" variant="{{ $tab === 'categories' ? 'primary' : 'ghost' }}">Categorias</x-ui.button>
            <x-ui.button size="sm" variant="{{ $tab === 'cards' ? 'primary' : 'ghost' }}">Cartões</x-ui.button>
        </div>

        <div class="mt-2 ring-offset-background focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 space-y-4">
            @if($tab === 'overview')
                @include('partials.overview')
            @elseif($tab === 'transactions')
                @include('partials.transactions')
            @elseif($tab === 'categories')
                @include('partials.categories')
            @elseif($tab === 'cards')
                @include('partials.cards')
            @endif
        </div>
    </div>
    <x-ui.modal
        id="transactionModal"
        width="lg"
        position="center"
        heading="Nova Transação"
        description="Preencha os detalhes da nova transação abaixo"
    >
        <x-ui.field class="mb-4">
            <x-ui.label>Nome</x-ui.label>
            <x-ui.input wire:model="name" placeholder="Ex: Salário, Aluguel, etc."/>
            <x-ui.error name="name" />
        </x-ui.field>

        <x-ui.field class="mb-4">
            <x-ui.label>Valor</x-ui.label>
            <x-ui.input wire:model="amount" placeholder="R$ 0,00" x-ref="money"
                        x-on:input="
            let value = $refs.money.value.replace(/\D/g, '');
            value = (value / 100).toFixed(2) + '';
            value = value.replace('.', ',');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $refs.money.value = 'R$ ' + value;
        "/>
            <x-ui.error name="amount" />
        </x-ui.field>

        <x-ui.radio.group wire:model="type" label="Tipo" variant="segmented" direction="horizontal" class="mb-4">
            <x-ui.radio.item
                value="Receita"
                label="Receita"
            />
            <x-ui.radio.item
                value="Despesa"
                label="Despesa"
            />
        </x-ui.radio.group>

        <x-ui.field class="mb-4">
            <x-ui.label>Categoria</x-ui.label>
            <x-ui.select
                placeholder="Selecione uma categoria"
                wire:model="category">
                <x-ui.select.option value="option1">Option 1</x-ui.select.option>
                <x-ui.select.option value="option2">Option 2</x-ui.select.option>
            </x-ui.select>
            <x-ui.error name="category" />
        </x-ui.field>

        <x-ui.field class="mb-4">
            <x-ui.label>Cartão de crédito (opcional)</x-ui.label>
            <x-ui.select
                placeholder="Selecione um cartão"
                wire:model="card">
                <x-ui.select.option value="option1">Option 1</x-ui.select.option>
                <x-ui.select.option value="option2">Option 2</x-ui.select.option>
            </x-ui.select>
            <x-ui.error name="card" />
        </x-ui.field>

        <x-ui.field class="mb-4">
            <x-ui.label>Data</x-ui.label>
            <x-ui.input type="date" wire:model="date"/>
            <x-ui.error name="date" />
        </x-ui.field>

        <x-ui.field class="mb-4">
            <x-ui.label>Descrição (opcional)</x-ui.label>
            <x-ui.textarea
                wire:model="description"
                placeholder="Descrição..."
            />
        </x-ui.field>

        <x-slot name="footer">
            <div class="w-full flex justify-end space-x-3">
                <x-ui.button x-on:click="$data.close();" variant="outline">
                    Cancelar
                </x-ui.button>
                <x-ui.button x-on:click="$data.close();" variant="primary">
                    Salvar
                </x-ui.button>
            </div>
        </x-slot>
    </x-ui.modal>
</div>
