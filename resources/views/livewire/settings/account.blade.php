<div class="space-y-20 pb-8">
    <div>
        <flux:heading size="lg" level="1" class="!mb-0">Informações da Conta</flux:heading>
        <flux:subheading size="md">Atualize suas credenciais</flux:subheading>


        <div class="grow">
            <form
                wire:submit="saveChanges"
                class="mt-8 space-y-4 rounded-lg bg-neutral-50 p-6 dark:bg-gray-800/80 shadow"
            >
                <flux:field>
                    <flux:label>Nome</flux:label>
                    <flux:input wire:model="name" />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>Endereço de e-mail</flux:label>
                    <flux:input wire:model="email" />
                    <flux:error name="email" type="email"/>
                </flux:field>

                <flux:button variant="primary" type="submit" class="cursor-pointer">Salvar</flux:button>
            </form>
        </div>
    </div>

    <div>
        <flux:heading size="lg" level="1" class="!mb-0">Alterar senha</flux:heading>
        <flux:subheading size="md">Atualize suas credenciais de segurança</flux:subheading>

        <form
            wire:submit="updatePassword"
            class="mt-8 space-y-4 rounded-lg bg-neutral-50 p-6 dark:bg-gray-800/80 shadow"
        >
            <flux:field>
                <flux:label>Senha atual</flux:label>
                <flux:input wire:model="current_password" type="password"/>
                <flux:error name="current_password" />
            </flux:field>

            <flux:field>
                <flux:label>Nova senha</flux:label>
                <flux:input wire:model="password" type="password"/>
                <flux:error name="password" />
            </flux:field>

            <flux:field>
                <flux:label>Confirmar nova senha</flux:label>
                <flux:input wire:model="password_confirmation" type="password"/>
                <flux:error name="password_confirmation"/>
            </flux:field>

            <flux:button variant="primary" type="submit" class="cursor-pointer">Alterar senha</flux:button>
        </form>
    </div>
</div>
