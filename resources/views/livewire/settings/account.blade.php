<div class="space-y-20 mt-20  px-4">
    <div>
        <x-ui.heading>Informações da Conta</x-ui.heading>
        <x-ui.text class="opacity-50">Atualize suas credenciais públicas</x-ui.text>
        <div class="grow">
            <form
                wire:submit="saveChanges"
                class="mt-8 space-y-4 rounded-lg bg-neutral-50 p-6 dark:bg-neutral-800/10 shadow"
            >
                <x-ui.field>
                    <x-ui.label>Nome</x-ui.label>
                    <x-ui.input wire:model="name" />
                    <x-ui.error name="name" />
                </x-ui.field>

                <x-ui.field>
                    <x-ui.label>Endereço de e-mail</x-ui.label>
                    <x-ui.input
                        wire:model="email"
                        type="email"
                        copyable
                    />
                    <x-ui.error name="email" />
                </x-ui.field>
                <x-ui.button
                    type="submit"
                >Salvar alterações</x-ui.button>
            </form>
        </div>
    </div>

    <div>
        <x-ui.heading>Alterar senha</x-ui.heading>
        <x-ui.text class="opacity-50">Atualize suas credenciais de segurança</x-ui.text>
        <form
            wire:submit="updatePassword"
            class="mt-8 space-y-4 rounded-lg bg-neutral-50 p-6 dark:bg-neutral-800/10 shadow"
        >

            <x-ui.field>
                <x-ui.label>Senha atual</x-ui.label>
                <x-ui.input
                    wire:model="current_password"
                    type="password"
                    revealable
                />
                <x-ui.error name="current_password" />
            </x-ui.field>

            <x-ui.field>
                <x-ui.label>Nova senha</x-ui.label>
                <x-ui.input
                    wire:model="password"
                    type="password"
                    revealable
                />
                <x-ui.error name="password" />
            </x-ui.field>

            <x-ui.field>
                <x-ui.label>Confirmar nova senha</x-ui.label>
                <x-ui.input
                    wire:model="password_confirmation"
                    type="password"
                    revealable
                />
                <x-ui.error name="password_confirmation" />
            </x-ui.field>

            <x-ui.button
                type="submit"
                class="mt-6"
            >
                Alterar senha
            </x-ui.button>
        </form>
    </div>
</div>
