<flux:modal name="modal-category" class="w-[90%] max-w-100" @close="resetForm" :dismissible="false">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">{{ $category ? 'Editar Categoria' : 'Nova Categoria' }}</flux:heading>
            <flux:text class="mt-2">
                {{ $category ? 'Atualize os dados da categoria abaixo.' : 'Preencha os detalhes da nova categoria abaixo.' }}
            </flux:text>
        </div>

        <div class="space-y-4">
            <flux:field>
                <flux:label>Nome</flux:label>
                <flux:input wire:model.live.debounce.500ms="name" placeholder="Ex: Alimentação, Moradia, etc."/>
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Tipo</flux:label>
                <flux:radio.group wire:model.live.debounce.500ms="type" variant="segmented">
                    <flux:radio label="Receita" value="Receita" class="cursor-pointer"/>
                    <flux:radio label="Despesa" value="Despesa" class="cursor-pointer"/>
                    <flux:radio label="Ambos" value="Ambos" class="cursor-pointer"/>
                </flux:radio.group>
                <flux:error name="type" />
            </flux:field>
        </div>

        <div class="flex">
            <flux:spacer />
            <flux:button wire:click="save" variant="primary" class="cursor-pointer" wire:target="save, name, type">
                {{ $category ? 'Atualizar' : 'Salvar' }}
            </flux:button>
        </div>
    </div>
</flux:modal>