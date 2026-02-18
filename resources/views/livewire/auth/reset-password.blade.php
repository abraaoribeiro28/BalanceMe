<div class="flex flex-col gap-6">
    <div class="flex w-full flex-col text-center">
        <flux:heading size="xl">Reset your password</flux:heading>
        <flux:subheading>Set a new password for your account.</flux:subheading>
    </div>

    <form wire:submit="resetPassword" class="flex flex-col gap-6">
        <flux:input
            wire:model.blur="email"
            label="Email address"
            type="email"
            required
            autocomplete="email"
            placeholder="email@exemplo.com"
        />

        <flux:input
            wire:model.blur="password"
            label="Password"
            type="password"
            required
            autocomplete="new-password"
            placeholder="Enter your new password"
            viewable
        />

        <flux:input
            wire:model.blur="password_confirmation"
            label="Password confirmation"
            type="password"
            required
            autocomplete="new-password"
            placeholder="Confirm your new password"
            viewable
        />

        <flux:button class="w-full" variant="primary" type="submit">
            Reset
        </flux:button>
    </form>
</div>
