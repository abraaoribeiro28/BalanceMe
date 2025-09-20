@props(['title', 'description'])
<div class="sm:flex items-center justify-between">
    <div class="sm:mb-0 mb-2">
        <h1 class="font-heading text-3xl md:text-4xl">{{ $title }}</h1>
        <p class="text-lg text-gray-500 dark:text-gray-300">{{ $description }}</p>
    </div>
    {{ $slot }}
</div>
