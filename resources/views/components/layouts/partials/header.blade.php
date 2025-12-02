<flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700 transition-colors">
    <flux:brand logo="{{ asset('images/logo.webp') }}" name="BalanceMe"/>

    @auth
        <flux:navbar class="-mb-px  max-md:hidden">
            <flux:navbar.item icon="chart-bar" href="{{ route('dashboard') }}">Dashboard</flux:navbar.item>
        </flux:navbar>
    @endauth

    <flux:spacer />

    @guest
        <flux:navbar class="-mb-px">
            <flux:button href="{{ route('login') }}" size="sm" variant="filled">Entrar</flux:button>
            <flux:button href="{{ route('register') }}" size="sm" variant="primary" class="ml-1">Registre-se</flux:button>
        </flux:navbar>
    @else
        <flux:navbar class="-mb-px">
            <flux:dropdown position="top" align="start">
                <flux:profile :name="auth()->user()->name" class="cursor-pointer"/>
                <flux:navmenu>
                    <flux:navmenu.item href="{{ route('dashboard') }}" icon="chart-bar" class="hidden max-sm:flex">Dashboard</flux:navmenu.item>
                    <flux:navmenu.item href="{{ route('settings.account') }}" icon="user">Minha conta</flux:navmenu.item>
                    <form method="POST" action="{{ route('app.auth.logout') }}" class="w-full">
                        @csrf
                        <flux:navmenu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:navmenu.item>
                    </form>
                </flux:navmenu>
            </flux:dropdown>
        </flux:navbar>
    @endguest

    <flux:button x-data x-on:click="$flux.dark = ! $flux.dark">Toggle</flux:button>
</flux:header>
