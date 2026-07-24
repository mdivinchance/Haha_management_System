<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Haha_system') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
            <div class="mb-6 text-center">
                <a href="/" class="text-3xl font-bold text-teal-400 tracking-tight">HAHA</a>
                <p class="text-xs text-gray-500 dark:text-neutral-500 mt-1 tracking-widest uppercase">Inventory System</p>
            </div>

            <div class="w-full sm:max-w-md">
                <div class="panel">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
