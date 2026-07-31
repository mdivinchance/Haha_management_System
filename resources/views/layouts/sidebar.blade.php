@php
    $user = Auth::user();
    $navItems = [
        ['route' => 'dashboard', 'name' => 'Dashboard', 'icon' => 'grid'],
    ];
    if ($user->isSuperAdmin()) {
        $navItems[] = ['route' => 'users.index', 'name' => 'Users', 'icon' => 'users'];
    }
    if ($user->isManager()) {
        $navItems[] = ['route' => 'categories.index', 'name' => 'Categories', 'icon' => 'tag'];
        $navItems[] = ['route' => 'products.index', 'name' => 'Products', 'icon' => 'box'];
        $navItems[] = ['route' => 'money-report.index', 'name' => 'Money', 'icon' => 'money'];
    }
@endphp

{{-- Desktop sidebar (lg+) --}}
<aside class="fixed inset-y-0 left-0 z-50 hidden w-64 flex-col bg-white border-r border-gray-200 dark:bg-neutral-900 dark:border-neutral-800 lg:flex">
    <div class="flex flex-col h-full">
        <div class="px-6 py-6">
            <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-teal-400 tracking-tight">HAHA</a>
            <p class="text-[10px] text-gray-500 dark:text-neutral-500 mt-0.5 tracking-[0.2em] uppercase">Inventory System</p>
        </div>

        <nav class="flex-1 space-y-0.5 px-3">
            @foreach ($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="sidebar-link {{ request()->routeIs($item['route'] . '*') ? 'sidebar-link-active' : '' }}">
                    <span class="font-medium">{{ $item['name'] }}</span>
                    <span class="text-[11px] text-gray-500 dark:text-neutral-500 mt-0.5">{{ $item['route'] === 'dashboard' ? ($user->isSuperAdmin() ? 'Manager overview' : 'Overview &amp; metrics') : ($item['route'] === 'users.index' ? 'Manage managers' : ($item['route'] === 'categories.index' ? 'Manage product types' : ($item['route'] === 'products.index' ? 'Inventory items' : 'Revenue &amp; sales summary'))) }}</span>
                </a>
            @endforeach
        </nav>

        <div class="border-t border-gray-200 dark:border-neutral-800 px-3 py-4 space-y-3">
            <div class="flex items-center gap-3 px-3">
                <div class="h-9 w-9 rounded-full bg-teal-500/20 flex items-center justify-center text-sm font-semibold text-teal-400">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $user->name }}</p>
                    <p class="text-[11px] text-gray-500 dark:text-neutral-500 truncate">{{ $user->isSuperAdmin() ? 'Super Admin' : 'Manager' }}</p>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}"
               class="sidebar-link {{ request()->routeIs('profile.*') ? 'sidebar-link-active' : '' }}">
                <span class="font-medium">Settings</span>
                <span class="text-[11px] text-gray-500 dark:text-neutral-500 mt-0.5">Profile &amp; credentials</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-500/10 border-l-[3px] border-transparent hover:border-red-400">
                    <span class="font-medium">Logout</span>
                    <span class="text-[11px] text-gray-500 dark:text-neutral-500 mt-0.5">End session</span>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Mobile bottom nav (< lg) --}}
<nav class="lg:hidden fixed bottom-0 inset-x-0 z-50 bg-white border-t border-gray-200 dark:bg-neutral-900 dark:border-neutral-800" x-data="{ settingsOpen: false }">
    <div class="flex items-stretch justify-around">
        @foreach ($navItems as $item)
            <a href="{{ route($item['route']) }}"
               class="flex-1 flex flex-col items-center justify-center py-2 px-1 transition-colors {{ request()->routeIs($item['route'] . '*') ? 'text-teal-400' : 'text-gray-500 dark:text-neutral-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if ($item['icon'] === 'grid')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    @elseif ($item['icon'] === 'users')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    @elseif ($item['icon'] === 'tag')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    @elseif ($item['icon'] === 'box')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    @elseif ($item['icon'] === 'money')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    @endif
                </svg>
                <span class="text-[10px] mt-0.5 font-medium">{{ $item['name'] }}</span>
            </a>
        @endforeach

        <div class="relative flex-1">
            <button @click="settingsOpen = !settingsOpen"
                    class="w-full flex flex-col items-center justify-center py-2 px-1 transition-colors {{ request()->routeIs('profile.*') ? 'text-teal-400' : 'text-gray-500 dark:text-neutral-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-[10px] mt-0.5 font-medium">Settings</span>
            </button>

            <div x-show="settingsOpen" @click.away="settingsOpen = false" x-cloak x-transition
                 class="absolute bottom-full mb-2 right-0 w-48 rounded-xl shadow-2xl z-50 bg-white border border-gray-200 dark:bg-neutral-800 dark:border-neutral-700 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-neutral-700 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-full bg-teal-500/20 flex items-center justify-center text-sm font-semibold text-teal-400 flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $user->name }}</p>
                        <p class="text-[11px] text-gray-500 dark:text-neutral-500 truncate">{{ $user->isSuperAdmin() ? 'Super Admin' : 'Manager' }}</p>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" @click="settingsOpen = false" class="flex items-center gap-3 w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-700">
                    <svg class="w-4 h-4 text-gray-500 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profile
                </a>
                <a href="{{ route('profile.edit') }}#password" @click="settingsOpen = false" class="flex items-center gap-3 w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-700">
                    <svg class="w-4 h-4 text-gray-500 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    Change Password
                </a>
                <div class="border-t border-gray-200 dark:border-neutral-700">
                    <p class="px-4 pt-2.5 pb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400 dark:text-neutral-500">Theme</p>
                    <button @click="localStorage.setItem('theme', 'dark'); document.documentElement.setAttribute('data-theme', 'dark'); document.documentElement.classList.add('dark'); settingsOpen = false" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-700">
                        <svg class="w-4 h-4 text-gray-500 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        Dark
                    </button>
                    <button @click="localStorage.setItem('theme', 'light'); document.documentElement.setAttribute('data-theme', 'light'); document.documentElement.classList.remove('dark'); settingsOpen = false" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-700">
                        <svg class="w-4 h-4 text-gray-500 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Light
                    </button>
                    <button @click="const r = window.matchMedia('(prefers-color-scheme: dark)').matches; localStorage.setItem('theme', 'system'); document.documentElement.setAttribute('data-theme', r ? 'dark' : 'light'); r ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark'); settingsOpen = false" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-700">
                        <svg class="w-4 h-4 text-gray-500 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        System
                    </button>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-200 dark:border-neutral-700">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-sm text-red-500 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
