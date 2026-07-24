@php
    $user = Auth::user();
@endphp

<aside class="fixed inset-y-0 left-0 z-50 hidden w-64 flex-col bg-white border-r border-gray-200 dark:bg-neutral-900 dark:border-neutral-800 lg:flex">
    <div class="flex flex-col h-full">
        <div class="px-6 py-6">
            <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-teal-400 tracking-tight">HAHA</a>
            <p class="text-[10px] text-gray-500 dark:text-neutral-500 mt-0.5 tracking-[0.2em] uppercase">Inventory System</p>
        </div>

        <nav class="flex-1 space-y-0.5 px-3">
            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'sidebar-link-active' : '' }}">
                <span class="font-medium">Dashboard</span>
                <span class="text-[11px] text-gray-500 dark:text-neutral-500 mt-0.5">{{ $user->isSuperAdmin() ? 'Manager overview' : 'Overview &amp; metrics' }}</span>
            </a>

            @if($user->isSuperAdmin())
                <a href="{{ route('users.index') }}"
                   class="sidebar-link {{ request()->routeIs('users.*') ? 'sidebar-link-active' : '' }}">
                    <span class="font-medium">Users</span>
                    <span class="text-[11px] text-gray-500 dark:text-neutral-500 mt-0.5">Manage managers</span>
                </a>
            @endif

            @if($user->isManager())
                <a href="{{ route('categories.index') }}"
                   class="sidebar-link {{ request()->routeIs('categories.*') ? 'sidebar-link-active' : '' }}">
                    <span class="font-medium">Categories</span>
                    <span class="text-[11px] text-gray-500 dark:text-neutral-500 mt-0.5">Manage product types</span>
                </a>

                <a href="{{ route('products.index') }}"
                   class="sidebar-link {{ request()->routeIs('products.*') ? 'sidebar-link-active' : '' }}">
                    <span class="font-medium">Products</span>
                    <span class="text-[11px] text-gray-500 dark:text-neutral-500 mt-0.5">Inventory items</span>
                </a>

                <a href="{{ route('money-report.index') }}"
                   class="sidebar-link {{ request()->routeIs('money-report.*') ? 'sidebar-link-active' : '' }}">
                    <span class="font-medium">Money Report</span>
                    <span class="text-[11px] text-gray-500 dark:text-neutral-500 mt-0.5">Revenue &amp; sales summary</span>
                </a>
            @endif
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

<nav x-data="{ open: false }" class="lg:hidden bg-white border-b border-gray-200 dark:bg-neutral-900 dark:border-neutral-800 px-4 py-3">
    <div class="flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-teal-400">HAHA</a>
        <button @click="open = !open" class="text-gray-500 hover:text-gray-900 dark:text-neutral-400 dark:hover:text-white">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div :class="{'block': open, 'hidden': !open}" class="hidden pt-4 pb-2 space-y-1">
        <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-sm rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-neutral-300 dark:hover:text-white dark:hover:bg-neutral-800 {{ request()->routeIs('dashboard') ? 'text-teal-400 bg-gray-100 dark:bg-neutral-800' : '' }}">Dashboard</a>
        @if($user->isSuperAdmin())
            <a href="{{ route('users.index') }}" class="block px-3 py-2 text-sm rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-neutral-300 dark:hover:text-white dark:hover:bg-neutral-800 {{ request()->routeIs('users.*') ? 'text-teal-400 bg-gray-100 dark:bg-neutral-800' : '' }}">Users</a>
        @endif
        @if($user->isManager())
            <a href="{{ route('categories.index') }}" class="block px-3 py-2 text-sm rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-neutral-300 dark:hover:text-white dark:hover:bg-neutral-800 {{ request()->routeIs('categories.*') ? 'text-teal-400 bg-gray-100 dark:bg-neutral-800' : '' }}">Categories</a>
            <a href="{{ route('products.index') }}" class="block px-3 py-2 text-sm rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-neutral-300 dark:hover:text-white dark:hover:bg-neutral-800 {{ request()->routeIs('products.*') ? 'text-teal-400 bg-gray-100 dark:bg-neutral-800' : '' }}">Products</a>
            <a href="{{ route('money-report.index') }}" class="block px-3 py-2 text-sm rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-neutral-300 dark:hover:text-white dark:hover:bg-neutral-800 {{ request()->routeIs('money-report.*') ? 'text-teal-400 bg-gray-100 dark:bg-neutral-800' : '' }}">Money Report</a>
        @endif
        <hr class="border-gray-200 dark:border-neutral-800 my-2">
        <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-neutral-300 dark:hover:text-white dark:hover:bg-neutral-800 {{ request()->routeIs('profile.*') ? 'text-teal-400 bg-gray-100 dark:bg-neutral-800' : '' }}">Settings</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left px-3 py-2 text-sm rounded-lg text-red-500 hover:text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-500/10">Logout</button>
        </form>
    </div>
</nav>
