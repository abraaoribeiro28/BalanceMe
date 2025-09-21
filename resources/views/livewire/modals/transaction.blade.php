<x-ui.modal
    id="modal-transaction"
    width="lg"
    backdrop="dark"
    position="center"
    heading="Nova Transação"
    description="Preencha os detalhes da nova transação abaixo"
>
    <x-ui.field class="mb-4">
        <x-ui.label>Nome</x-ui.label>
        <x-ui.input wire:model.live.debounce.500ms="name" placeholder="Ex: Salário, Aluguel, etc."/>
        <x-ui.error name="name" />
    </x-ui.field>

    <x-ui.field class="mb-4">
        <x-ui.label>Valor</x-ui.label>
        <x-ui.input wire:model.live.debounce.500ms="amount" placeholder="R$ 0,00"
            x-ref="money"
            x-on:input="
                let value = $refs.money.value.replace(/\D/g, '');
                value = (value / 100).toFixed(2) + '';
                value = value.replace('.', ',');
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                $refs.money.value = 'R$ ' + value;
            "
        />
        <x-ui.error name="amount" />
    </x-ui.field>

    <x-ui.field class="mb-4">
        <x-ui.radio.group wire:model.live.debounce.500ms="type" label="Tipo" variant="segmented" direction="horizontal">
            <x-ui.radio.item
                value="Receita"
                label="Receita"
            />
            <x-ui.radio.item
                value="Despesa"
                label="Despesa"
            />
        </x-ui.radio.group>
        <x-ui.error name="type" />
    </x-ui.field>

    <x-ui.field class="mb-4">
        <x-ui.label>Categoria</x-ui.label>
        <x-ui.select
            placeholder="Selecione uma categoria"
            wire:model.live.debounce.500ms="category_id">
            @foreach($categories as $id => $name)
                <x-ui.select.option value="{{$id}}">{{ $name }}</x-ui.select.option>
            @endforeach
        </x-ui.select>
        <x-ui.error name="category_id" />
    </x-ui.field>

    <x-ui.field class="mb-4">
        <x-ui.label>Cartão de crédito (opcional)</x-ui.label>
        <x-ui.select
            placeholder="Selecione um cartão"
            wire:model.live.debounce.500ms="card_id">
            @foreach($cards as $id => $name)
                <x-ui.select.option value="{{$id}}">{{ $name }}</x-ui.select.option>
            @endforeach
        </x-ui.select>
        <x-ui.error name="card_id" />
    </x-ui.field>

    <x-ui.field class="mb-4">
        <x-ui.label>Data</x-ui.label>
        <x-ui.input type="date" wire:model.live.debounce.500ms="date"/>
        <x-ui.error name="date" />
    </x-ui.field>

    <x-ui.field class="mb-4">
        <x-ui.label>Descrição (opcional)</x-ui.label>
        <x-ui.textarea
            wire:model.live.debounce.500ms="description"
            placeholder="Descrição..."
        />
        <x-ui.error name="description" />
    </x-ui.field>

    <x-slot name="footer">
        <div class="w-full flex justify-end space-x-3">
            <x-ui.button x-on:click="$data.close();" variant="outline">
                Cancelar
            </x-ui.button>
            <x-ui.button wire:click="save()" wire:loading variant="primary">
                Salvar
            </x-ui.button>
        </div>
    </x-slot>
</x-ui.modal>
