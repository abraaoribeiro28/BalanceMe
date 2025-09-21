<x-slot:title>
    Login to Sheaf
</x-slot>

<form
    wire:submit="login"
    class="mx-auto w-full max-w-md space-y-4 px-6"
>

    <div class="space-y-4">
        <x-ui.field>
            <x-ui.label>E-mail</x-ui.label>
            <x-ui.input
                wire:model="form.email"
            />
            <x-ui.error name="form.email" />
        </x-ui.field>

        <x-ui.field>
            <x-ui.label>Senha</x-ui.label>
            <x-ui.input
                wire:model="form.password"
                type='password'
                revealable
            />
            <x-ui.error name="form.password" />
        </x-ui.field>
    </div>

    <x-ui.button
        class="w-full"
        type="submit"
    >
        Entrar
    </x-ui.button>

    <x-ui.link
        variant="soft"
        href="{{ route('register') }}"
    >
        Não tenho uma conta?
        <span class="underline">Registre-se</span>
    </x-ui.link>
</form>
