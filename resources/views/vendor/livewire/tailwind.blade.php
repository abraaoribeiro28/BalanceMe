@php
    if (! isset($scrollTo)) {
        $scrollTo = 'body';
    }

    $scrollIntoViewJsSnippet = ($scrollTo !== false)
        ? <<<JS
           (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
        JS
        : '';
@endphp

@if ($paginator->hasPages())
    <div
        class="flex items-center justify-between"
        {{-- ajuda o Livewire a não se perder entre páginas --}}
        wire:key="pagination-{{ $paginator->getPageName() }}-{{ $paginator->currentPage() }}"
    >
        {{-- Controles à esquerda --}}
        <div class="flex items-center space-x-2">

            {{-- Anterior --}}
            @if ($paginator->onFirstPage())
                <button
                    class="ring-offset-background focus-visible:outline-hidden focus-visible:ring-ring inline-flex items-center justify-center gap-2
                        whitespace-nowrap text-sm font-medium transition-colors focus-visible:ring-2 focus-visible:ring-offset-2
                        disabled:pointer-events-none disabled:opacity-50 border-input bg-background border border-zinc-300 dark:border-white/20
                        h-9 rounded-md px-3"
                    disabled
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-chevron-left h-4 w-4">
                        <path d="m15 18-6-6 6-6"></path>
                    </svg>
                    Anterior
                </button>
            @else
                <button
                    type="button"
                    wire:click="previousPage('{{ $paginator->getPageName() }}')"
                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                    class="cursor-pointer ring-offset-background focus-visible:outline-hidden focus-visible:ring-ring inline-flex items-center justify-center gap-2
                        whitespace-nowrap text-sm font-medium transition-colors focus-visible:ring-2 focus-visible:ring-offset-2
                        border-input bg-background hover:bg-accent hover:text-accent-foreground border border-zinc-300 dark:border-white/20
                        h-9 rounded-md px-3"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-chevron-left h-4 w-4">
                        <path d="m15 18-6-6 6-6"></path>
                    </svg>
                    Anterior
                </button>
            @endif

            {{-- Páginas --}}
            <div class="flex items-center space-x-1">
                @foreach ($elements as $element)

                    {{-- Separador "..." --}}
                    @if (is_string($element))
                        <button
                            class="ring-offset-background focus-visible:outline-hidden focus-visible:ring-ring inline-flex items-center justify-center
                                text-sm font-medium transition-colors focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none
                                disabled:opacity-50 border-input bg-background border border-zinc-300 dark:border-white/20 rounded-md w-8 h-8 p-0"
                            disabled
                        >…</button>
                    @endif

                    {{-- Links de páginas --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)

                            {{-- Página selecionada --}}
                            @if ($page == $paginator->currentPage())
                                <button
                                    class="bg-accent inline-flex items-center justify-center w-8 h-8 p-0 rounded-md font-semibold shadow-sm
                                        bg-primary text-white border border-accent"
                                    aria-current="page"
                                >
                                    {{ $page }}
                                </button>

                                {{-- Páginas normais --}}
                            @else
                                <button
                                    type="button"
                                    wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                    class="cursor-pointer ring-offset-background focus-visible:outline-hidden focus-visible:ring-ring inline-flex items-center justify-center
                                        whitespace-nowrap text-sm font-medium transition-colors focus-visible:ring-2 focus-visible:ring-offset-2
                                        border-input bg-background hover:bg-accent hover:text-accent-foreground border border-zinc-300
                                        dark:border-white/20 w-8 h-8 rounded-md p-0"
                                    aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                >
                                    {{ $page }}
                                </button>
                            @endif

                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Próxima --}}
            @if ($paginator->hasMorePages())
                <button
                    type="button"
                    wire:click="nextPage('{{ $paginator->getPageName() }}')"
                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                    class="cursor-pointer ring-offset-background focus-visible:outline-hidden focus-visible:ring-ring inline-flex items-center justify-center gap-2
                        whitespace-nowrap text-sm font-medium transition-colors focus-visible:ring-2 focus-visible:ring-offset-2
                        border-input bg-background hover:bg-accent hover:text-accent-foreground border border-zinc-300 dark:border-white/20
                        h-9 rounded-md px-3"
                >
                    Próxima
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-chevron-right h-4 w-4">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </button>
            @else
                <button
                    class="cursor-pointer ring-offset-background focus-visible:outline-hidden focus-visible:ring-ring inline-flex items-center justify-center gap-2
                        whitespace-nowrap text-sm font-medium transition-colors focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none
                        disabled:opacity-50 border-input bg-background border border-zinc-300 dark:border-white/20 h-9 rounded-md px-3"
                    disabled
                >
                    Próxima
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-chevron-right h-4 w-4">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </button>
            @endif
        </div>

        {{-- Texto à direita --}}
        <div class="text-sm text-gray-500 dark:text-gray-300">
            Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}
        </div>
    </div>
@endif
