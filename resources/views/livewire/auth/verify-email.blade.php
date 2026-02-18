<div class="mx-auto mt-10 flex w-full max-w-2xl flex-col gap-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
    <div class="flex w-full flex-col text-center">
        <flux:heading size="xl">Please verify your email</flux:heading>
        <flux:subheading>
            {{ __('Thanks for signing up! Before getting started, verify your email address using the link we sent.') }}
        </flux:subheading>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="rounded-md border border-emerald-500/40 bg-emerald-500/10 p-3 text-sm text-emerald-700 dark:text-emerald-300">
            {{ __('A new verification link has been sent to your email address.') }}
        </div>
    @endif

    <div class="flex justify-center">
        <flux:button wire:click="sendVerification" variant="primary">
            {{ __('Resend verification email') }}
        </flux:button>
    </div>
</div>
