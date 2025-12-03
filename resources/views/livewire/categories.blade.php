<div>
    <div class="rounded-lg border border-gray-200 dark:border-transparent  bg-white dark:bg-white/5 transition-colors">
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
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($categories as $category)
                        <x-app.category :label="$category->name" color="!bg-[{{$category->color}}]" :type="$category->type"/>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <livewire:modals.category/>
</div>

