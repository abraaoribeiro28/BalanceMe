<flux:modal name="modal-cards" class="w-[90%] max-w-100" @close="resetForm" :dismissible="false">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Novo Cartão de Crédito</flux:heading>
            <flux:text class="mt-2">Preencha os detalhes do cartão abaixo.</flux:text>
        </div>

        <div class="space-y-4">
            <flux:field>
                <flux:label>Nome</flux:label>
                <flux:input wire:model.live.debounce.500ms="name" placeholder="Ex: Alimentação, Moradia, etc."/>
                <flux:error name="name" />
            </flux:field>
        </div>

        <div class="flex">
            <flux:spacer />
            <flux:button wire:click="save" variant="primary" class="cursor-pointer"
                         wire:target="save, name">
                Salvar
            </flux:button>
        </div>
    </div>
</flux:modal>

