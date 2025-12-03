<div>
    <div class="rounded-lg border border-gray-200 dark:border-transparent  bg-white dark:bg-white/5 transition-colors">
        <div class="flex flex-col space-y-1.5 p-6">
            <h3 class="text-2xl font-semibold leading-none tracking-tight">Gerenciar Cartões de Crédito</h3>
            <p class="text-sm text-gray-500 dark:text-gray-300">Adicione, edite ou remova cartões de crédito</p>
        </div>
        <div class="p-6 pt-0">
            <div class="space-y-4">
                <div class="sm:flex justify-between items-center">
                    <h3 class="text-lg font-medium sm:mb-0 mb-2">Seus Cartões de Crédito</h3>
                    <flux:modal.trigger name="modal-cards">
                        <flux:button variant="primary" icon="plus" class="sm:mt-0 mt-4 cursor-pointer">Nova Transação</flux:button>
                    </flux:modal.trigger>
                </div>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @forelse($cards as $card)
                        <x-app.card :label="$card->name"/>
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
</div>

