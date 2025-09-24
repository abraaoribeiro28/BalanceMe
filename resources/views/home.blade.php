<main>
    <section class="lg:min-h-screen w-full flex items-center">
        <div class="max-w-7xl mx-auto px-4 md:px-6 xl:py-0 pt-60 pb-40">
            <div class="grid gap-6 lg:grid-cols-2 lg:gap-12">
                <div class="flex flex-col justify-center space-y-4">
                    <div class="space-y-2">
                        <h1 class="text-3xl font-bold tracking-tighter sm:text-5xl">
                            Gerencie suas finanças com facilidade
                        </h1>
                        <p class="max-w-[600px] text-gray-500 dark:text-gray-300 md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed">
                            Controle suas receitas e despesas, visualize relatórios detalhados e tome decisões
                            financeiras mais inteligentes.</p>
                    </div>
                    <div class="flex flex-col gap-2 min-[400px]:flex-row">
                        <x-ui.button href="/register" icon-after="chevron-right">
                            Começar agora
                        </x-ui.button>
                        <x-ui.button variant="outline" href="/login">
                            Já tenho uma conta
                        </x-ui.button>
                    </div>
                </div>
                <div class="hidden lg:flex items-center justify-center">
                    <img alt="Dashboard Preview" class="dark:hidden rounded-lg object-cover border border-zinc-300 dark:border-white/20 shadow-2xs" src="{{ asset('images/dashboard-white.webp') }}">
                    <img alt="Dashboard Preview" class="hidden dark:inline-flex rounded-lg object-cover border border-zinc-300 dark:border-white/20 shadow-2xs" src="{{ asset('images/dashboard-dark.webp') }}">
                </div>
            </div>
        </div>
    </section>
    <section class="w-full py-12 md:py-24 lg:py-32 bg-gray-100 dark:bg-neutral-800">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="flex flex-col items-center justify-center space-y-4 text-center">
                <div class="space-y-2">
                    <h2 class="text-3xl font-bold tracking-tighter sm:text-4xl md:text-5xl">Recursos principais</h2>
                    <p
                        class="max-w-[900px] text-gray-500 md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed dark:text-gray-400">
                        Tudo o que você precisa para gerenciar suas finanças pessoais ou do seu pequeno negócio.</p>
                </div>
            </div>
            <div class="mx-auto grid max-w-5xl grid-cols-1 gap-6 py-12 md:grid-cols-3">
                <div class="rounded-lg border border-zinc-300 dark:border-white/20 shadow-2xs" data-v0-t="card">
                    <div class="space-y-1.5 p-6 flex flex-row items-center gap-4 pb-2"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chart-pie h-8 w-8 text-emerald-500">
                            <path
                                d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z">
                            </path>
                            <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                        </svg>
                        <h3 class="font-semibold tracking-tight text-xl">Dashboard Completo</h3>
                    </div>
                    <div class="p-6 pt-0">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Visualize seu saldo, receitas e despesas em
                            um painel intuitivo com gráficos detalhados.</p>
                    </div>
                </div>
                <div class="rounded-lg border border-zinc-300 dark:border-white/20 shadow-2xs" data-v0-t="card">
                    <div class="space-y-1.5 p-6 flex flex-row items-center gap-4 pb-2"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chart-line h-8 w-8 text-emerald-500">
                            <path d="M3 3v16a2 2 0 0 0 2 2h16"></path>
                            <path d="m19 9-5 5-4-4-3 3"></path>
                        </svg>
                        <h3 class="font-semibold tracking-tight text-xl">Gestão de Transações</h3>
                    </div>
                    <div class="p-6 pt-0">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Registre, edite e exclua transações
                            facilmente. Categorize e acompanhe para onde vai seu dinheiro.</p>
                    </div>
                </div>
                <div class="rounded-lg border border-zinc-300 dark:border-white/20 shadow-2xs" data-v0-t="card">
                    <div class="space-y-1.5 p-6 flex flex-row items-center gap-4 pb-2"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-users h-8 w-8 text-emerald-500">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <h3 class="font-semibold tracking-tight text-xl">Categorias Personalizáveis</h3>
                    </div>
                    <div class="p-6 pt-0">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Crie e personalize categorias para organizar
                            suas finanças de acordo com suas necessidades.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
