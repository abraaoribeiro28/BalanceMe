@props(['title', 'description'])

<div class="sm:flex items-center justify-between">
    <div>
        <flux:heading size="xl" level="1">{{ $title }}</flux:heading>
        <flux:subheading size="lg">{{ $description }}</flux:subheading>
    </div>
    {{ $slot }}
</div>
<flux:separator variant="subtle" />
