<x-ui.modal
    id="modal-category"
    width="md"
    backdrop="dark"
    position="center"
    heading="Nova Categoria"
    description="Preencha os detalhes da nova categoria abaixo"
>
    <x-ui.field class="mb-4">
        <x-ui.label>Nome</x-ui.label>
        <x-ui.input wire:model.live.debounce.500ms="name" placeholder="Ex: Alimentação, Moradia, etc."/>
        <x-ui.error name="name" />
    </x-ui.field>

    <x-ui.field class="mb-4">
        <x-ui.radio.group name="category_type" wire:model.live.debounce.500ms="type" label="Tipo" variant="segmented" direction="horizontal">
            <x-ui.radio.item value="Receita" label="Receita" />
            <x-ui.radio.item value="Despesa" label="Despesa" />
            <x-ui.radio.item value="Ambos" label="Ambos" />
        </x-ui.radio.group>
        <x-ui.error name="type" />
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
