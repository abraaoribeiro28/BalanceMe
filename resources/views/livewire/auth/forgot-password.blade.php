<x-slot:title>
    Forgot Password
</x-slot>

<div class="flex flex-col gap-6">
    <div class="flex w-full flex-col text-center">
        <flux:heading size="xl">Reset your password</flux:heading>
        <flux:subheading>Enter your email address and we will send a reset link.</flux:subheading>
    </div>

    @if (session('status'))
        <div class="rounded-md border border-emerald-500/40 bg-emerald-500/10 p-3 text-sm text-emerald-700 dark:text-emerald-300">
            An email has been sent to your mailbox.
        </div>
    @endif

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <flux:input
            wire:model.blur="email"
            label="Email address"
            type="email"
            required
            autocomplete="email"
            placeholder="email@exemplo.com"
        />

        <flux:button variant="primary" type="submit" class="w-full">
            Send link to email
        </flux:button>
    </form>

    <div class="text-center text-sm text-zinc-600 dark:text-zinc-400">
        <flux:link :href="route('login')" wire:navigate>
            Return to login? <span class="font-semibold">Log in</span>
        </flux:link>
    </div>
</div>
