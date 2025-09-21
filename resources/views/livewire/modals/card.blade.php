<x-ui.modal
    id="modal-card"
    width="md"
    backdrop="dark"
    position="center"
    heading="Novo Cartão de Crédito"
    description="Preencha os detalhes do cartão abaixo"
>
    <x-ui.field class="mb-4">
        <x-ui.label>Nome</x-ui.label>
        <x-ui.input wire:model.live.debounce.500ms="name" placeholder="Ex: Nubank, Inter, Santander..."/>
        <x-ui.error name="name" />
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

