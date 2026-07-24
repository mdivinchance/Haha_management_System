<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#14b8a6">

        <title>{{ config('app.name', 'Haha_system') }} @isset($title) - {{ $title }} @endisset</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <x-toast />
        <div class="flex min-h-screen">
            @include('layouts.sidebar')

            <div class="flex-1 lg:pl-64">
                <div class="p-4 sm:p-5 lg:p-6 xl:p-8 max-w-[1600px]">
                    <div x-data="{ theme: localStorage.getItem('theme') || 'dark' }" class="flex justify-end mb-3 sm:mb-4">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="inline-flex items-center gap-2 text-sm px-3 py-1.5 rounded-lg transition-colors bg-gray-200 text-gray-700 hover:text-gray-900 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:text-white">
                                <svg x-show="theme === 'dark'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                <svg x-show="theme === 'light'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <svg x-show="theme === 'system'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span class="hidden sm:inline capitalize" x-text="theme"></span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-36 rounded-lg shadow-lg z-50 bg-white border border-gray-200 dark:bg-neutral-800 dark:border-neutral-700">
                                <button @click="theme = 'dark'; localStorage.setItem('theme', 'dark'); document.documentElement.setAttribute('data-theme', 'dark'); document.documentElement.classList.add('dark'); open = false" class="block w-full text-left px-4 py-2 text-sm transition-colors text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-700">
                                    Dark
                                </button>
                                <button @click="theme = 'light'; localStorage.setItem('theme', 'light'); document.documentElement.setAttribute('data-theme', 'light'); document.documentElement.classList.remove('dark'); open = false" class="block w-full text-left px-4 py-2 text-sm transition-colors text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-700">
                                    Light
                                </button>
                                <button @click="theme = 'system'; localStorage.setItem('theme', 'system'); const r = window.matchMedia('(prefers-color-scheme: dark)').matches; document.documentElement.setAttribute('data-theme', r ? 'dark' : 'light'); r ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark'); open = false" class="block w-full text-left px-4 py-2 text-sm transition-colors text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-700">
                                    System
                                </button>
                            </div>
                        </div>
                    </div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
