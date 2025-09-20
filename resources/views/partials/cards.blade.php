<div class="rounded-lg border border-zinc-300 dark:border-white/20 shadow-2xs">
    <div class="flex flex-col space-y-1.5 p-6">
        <h3 class="text-2xl font-semibold leading-none tracking-tight">Gerenciar Cartões de Crédito</h3>
        <p class="text-sm text-gray-500 dark:text-gray-300">Adicione, edite ou remova cartões de crédito</p>
    </div>
    <div class="p-6 pt-0">
        <div class="space-y-4">
            <div class="sm:flex justify-between items-center">
                <h3 class="text-lg font-medium sm:mb-0 mb-2">Seus Cartões de Crédito</h3>
                <x-ui.button icon="plus">Adicionar cartão</x-ui.button>
            </div>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <x-app.card label="Nubank" color="bg-[#8a05be]"/>
                <x-app.card label="Santander" color="bg-[#ec0000]"/>
                <x-app.card label="Inter" color="bg-[#ff7a00]"/>
            </div>
        </div>
    </div>
</div>
