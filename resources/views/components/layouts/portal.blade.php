<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="bg-gray-50 dark:bg-gray-900 transition-colors">
        <x-layouts.partials.header/>
        {{ $slot }}
        <x-layouts.partials.footer/>
        @fluxScripts
    </body>
</html>
