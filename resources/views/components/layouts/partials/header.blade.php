<flux:header class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700 transition-colors">
    <div class="max-w-7xl w-full flex items-center mx-auto">
        <flux:brand href="#" logo="{{ asset('images/logo.webp') }}" name="BalanceMe" />
        @auth
            <flux:navbar class="-mb-px max-sm:hidden">
                <flux:navbar.item icon="chart-bar" href="{{ route('dashboard') }}">Dashboard</flux:navbar.item>
            </flux:navbar>
        @endauth

        <flux:spacer />

        @guest
            <flux:button href="{{ route('login') }}" variant="filled">Entrar</flux:button>
            <flux:button href="{{ route('register') }}" variant="primary" class="ml-2">Registre-se</flux:button>
        @else
            <flux:dropdown position="bottom" align="end">
                <flux:profile name="Olivia Martin" />
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
        @endguest
    </div>
</flux:header>
