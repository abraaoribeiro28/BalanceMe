<div>
    <div class="rounded-lg border border-gray-200 dark:border-transparent bg-white dark:bg-white/5 transition-colors">
        <div class="flex flex-col space-y-1.5 p-6">
            <h3 class="text-2xl font-semibold leading-none tracking-tight">Gerenciar Categorias</h3>
            <p class="text-sm text-gray-500 dark:text-gray-300">Adicione, edite ou remova categorias para organizar suas transações</p>
        </div>
        <div class="p-6 pt-0">
            <div class="space-y-4">
                <div class="sm:flex justify-between items-center">
                    <h3 class="text-lg font-medium sm:mb-0 mb-2">Suas Categorias</h3>
                    <flux:modal.trigger name="modal-category">
                        <flux:button variant="primary" icon="plus" class="sm:mt-0 mt-4 cursor-pointer">Adicionar categoria</flux:button>
                    </flux:modal.trigger>
                </div>

                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @forelse($categories as $category)
                        <x-app.category :label="$category->name" :type="$category->type">
                            <x-slot:actions>
                                <flux:button
                                    variant="filled"
                                    size="sm"
                                    icon="pencil-square"
                                    wire:click="editCategory({{ $category->id }})"
                                    class="cursor-pointer"
                                >
                                    Editar
                                </flux:button>

                                <flux:button
                                    variant="danger"
                                    size="sm"
                                    icon="trash"
                                    wire:click="confirmDeleteCategory({{ $category->id }})"
                                    class="cursor-pointer"
                                >
                                    Excluir
                                </flux:button>
                            </x-slot:actions>
                        </x-app.category>
                    @empty
                        <div class="flex h-[100px] items-center justify-center col-span-3">
                            <p class="text-gray-500 dark:text-gray-300">Nenhuma categoria encontrada.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <livewire:modals.category/>

    <flux:modal name="confirm-delete-category" class="w-[90%] max-w-lg" :dismissible="false" @close="resetDeleteCategoryState">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Excluir categoria?</flux:heading>
                <flux:text class="mt-2">Esta ação não pode ser desfeita.</flux:text>
                <flux:text class="mt-1">Categoria selecionada: <strong>{{ $categoryToDeleteName }}</strong></flux:text>
                <flux:text class="mt-1">As transações vinculadas a esta categoria também serão removidas.</flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer/>
                <flux:button variant="ghost" wire:click="closeDeleteCategoryModal" class="cursor-pointer">
                    Cancelar
                </flux:button>
                <flux:button variant="danger" icon="trash" wire:click="deleteCategoryConfirmed" class="cursor-pointer">
                    Excluir categoria
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>