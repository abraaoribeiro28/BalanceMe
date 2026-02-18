<div>
    <div class="rounded-lg border border-gray-200 dark:border-transparent bg-white dark:bg-white/5 transition-colors">
        <div class="flex flex-col space-y-1.5 p-6">
            <h3 class="text-2xl font-semibold leading-none tracking-tight">Gerenciar Cartões de Crédito</h3>
            <p class="text-sm text-gray-500 dark:text-gray-300">Adicione, edite ou remova cartões de crédito</p>
        </div>
        <div class="p-6 pt-0">
            <div class="space-y-4">
                <div class="sm:flex justify-between items-center">
                    <h3 class="text-lg font-medium sm:mb-0 mb-2">Seus Cartões de Crédito</h3>
                    <flux:modal.trigger name="modal-cards">
                        <flux:button variant="primary" icon="plus" class="sm:mt-0 mt-4 cursor-pointer">Adicionar cartão</flux:button>
                    </flux:modal.trigger>
                </div>

                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @forelse($cards as $card)
                        <x-app.card :label="$card->name">
                            <x-slot:actions>
                                <flux:button
                                    variant="filled"
                                    size="sm"
                                    icon="pencil-square"
                                    wire:click="editCard({{ $card->id }})"
                                    class="cursor-pointer"
                                >
                                    Editar
                                </flux:button>

                                <flux:button
                                    variant="danger"
                                    size="sm"
                                    icon="trash"
                                    wire:click="confirmDeleteCard({{ $card->id }})"
                                    class="cursor-pointer"
                                >
                                    Excluir
                                </flux:button>
                            </x-slot:actions>
                        </x-app.card>
                    @empty
                        <div class="flex h-[100px] items-center justify-center col-span-3">
                            <p class="text-gray-500 dark:text-gray-300">Nenhum cartão encontrado.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <livewire:modals.card/>

    <flux:modal name="confirm-delete-card" class="w-[90%] max-w-lg" :dismissible="false" @close="resetDeleteCardState">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Excluir cartão?</flux:heading>
                <flux:text class="mt-2">Esta ação não pode ser desfeita.</flux:text>
                <flux:text class="mt-1">Cartão selecionado: <strong>{{ $cardToDeleteName }}</strong></flux:text>
                <flux:text class="mt-1">As transações vinculadas a este cartão também serão removidas.</flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer/>
                <flux:button variant="ghost" wire:click="closeDeleteCardModal" class="cursor-pointer">
                    Cancelar
                </flux:button>
                <flux:button variant="danger" icon="trash" wire:click="deleteCardConfirmed" class="cursor-pointer">
                    Excluir cartão
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>