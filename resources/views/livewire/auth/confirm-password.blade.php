<div class="flex flex-col gap-6">
    <div class="flex w-full flex-col text-center">
        <flux:heading size="xl">Confirm your password</flux:heading>
        <flux:subheading>This is a secure area. Please confirm your password to continue.</flux:subheading>
    </div>

    <form wire:submit="confirmPassword" class="flex flex-col gap-6">
        <flux:input
            wire:model.blur="password"
            label="Password"
            type="password"
            required
            autocomplete="current-password"
            placeholder="Password"
            viewable
        />

        <flux:button variant="primary" type="submit" class="w-full">
            Confirm password
        </flux:button>
    </form>
</div>
