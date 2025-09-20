@props(['title', 'description'])
<div class="flex items-center justify-between px-2">
    <div class="grid gap-1">
        <h1 class="font-heading text-3xl md:text-4xl">{{ $title }}</h1>
        <p class="text-lg text-gray-500">{{ $description }}</p>
    </div>
    {{ $slot }}
</div>
