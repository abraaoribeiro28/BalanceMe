<x-slot:title>
    Register an account
</x-slot>

<form
    wire:submit="register"
    class="mx-auto w-full max-w-md space-y-4 px-6"
>
    <div class="space-y-4 gap-y-1">
        <x-ui.field >
            <x-ui.label>Nome</x-ui.label>
            <x-ui.input
                wire:model="form.name"
                placeholder="Digite seu nome"
            />
            <x-ui.error name="form.name" />
        </x-ui.field>

        <x-ui.field >
            <x-ui.label>E-mail</x-ui.label>
            <x-ui.input
                wire:model="form.email"
                type="email"
                placeholder="seu@email.com"
            />
            <x-ui.error name="form.email" />
        </x-ui.field>

        <x-ui.field >
            <x-ui.label>Senha</x-ui.label>
            <x-ui.input
                wire:model="form.password"
                type="password"
                revealable
                placeholder="Digite uma senha"
            />
            <x-ui.error name="form.password" />
        </x-ui.field>

        <x-ui.field >
            <x-ui.label>Corfirmação da senha</x-ui.label>
            <x-ui.input
                wire:model="form.password_confirmation"
                type="password"
                revealable
                placeholder="Confirme a senha"
            />
            <x-ui.error name="form.password_confirmation" />
        </x-ui.field>
    </div>

    <x-ui.button
        class="w-full"
        type="submit"
    >
        Registrar
    </x-ui.button>

    <x-ui.link
        variant="soft"
        href="{{ route('login') }}"
    >
        Já tem uma conta? <span class="underline">Entrar</span>
    </x-ui.link>
</form>
