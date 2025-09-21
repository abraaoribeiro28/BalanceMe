 @php
     $user = Auth::user();
 @endphp

 <x-ui.dropdown position="bottom-end">
    <x-slot:button class="justify-center">
        <x-ui.avatar
            class="cursor-pointer"
            :name="$user->name"
            size="sm"
        />
    </x-slot:button>

    <x-slot:menu class="w-56">
        <x-ui.dropdown.group label="Conectado como">
            <x-ui.dropdown.item>
                {{ $user->email }}
            </x-ui.dropdown.item>
        </x-ui.dropdown.group>

        <x-ui.dropdown.separator />

        <x-ui.dropdown.item :href="route('settings.account')" wire:navigate.live>
            Minha conta
        </x-ui.dropdown.item>

        <x-ui.dropdown.item :href="route('dashboard')" wire:navigate.live>
            Dashboard
        </x-ui.dropdown.item>

        <x-ui.dropdown.separator />

        <form
            action="{{ route('app.auth.logout') }}"
            method="post"
            class="contents"
        >
            @csrf
            <x-ui.dropdown.item as="button" type="submit">
                Sair
            </x-ui.dropdown.item>
        </form>

    </x-slot:menu>
</x-ui.dropdown>
