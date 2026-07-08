<aside class="fixed inset-y-0 left-0 z-50 hidden w-64 flex-col bg-neutral-900 border-r border-neutral-800 lg:flex">
    <div class="flex flex-col h-full">
        <div class="px-6 py-6">
            <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-teal-400 tracking-tight">HAHA</a>
            <p class="text-[10px] text-neutral-500 mt-0.5 tracking-[0.2em] uppercase">Inventory System</p>
        </div>

        <nav class="flex-1 space-y-0.5 px-3">
            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'sidebar-link-active' : 'text-neutral-400 hover:text-white' }}">
                <span class="font-medium">Dashboard</span>
                <span class="text-[11px] text-neutral-500 mt-0.5">Overview &amp; metrics</span>
            </a>

            <a href="{{ route('categories.index') }}"
               class="sidebar-link {{ request()->routeIs('categories.*') ? 'sidebar-link-active' : 'text-neutral-400 hover:text-white' }}">
                <span class="font-medium">Categories</span>
                <span class="text-[11px] text-neutral-500 mt-0.5">Manage product types</span>
            </a>

            <a href="{{ route('products.index') }}"
               class="sidebar-link {{ request()->routeIs('products.*') ? 'sidebar-link-active' : 'text-neutral-400 hover:text-white' }}">
                <span class="font-medium">Products</span>
                <span class="text-[11px] text-neutral-500 mt-0.5">Inventory items</span>
            </a>

            <a href="{{ route('money-report.index') }}"
               class="sidebar-link {{ request()->routeIs('money-report.*') ? 'sidebar-link-active' : 'text-neutral-400 hover:text-white' }}">
                <span class="font-medium">Money Report</span>
                <span class="text-[11px] text-neutral-500 mt-0.5">Revenue &amp; sales summary</span>
            </a>
        </nav>

        <div class="border-t border-neutral-800 px-3 py-4 space-y-3">
            <div class="flex items-center gap-3 px-3">
                <div class="h-9 w-9 rounded-full bg-teal-500/20 flex items-center justify-center text-sm font-semibold text-teal-400">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[11px] text-neutral-500 truncate">Administrator</p>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}"
               class="sidebar-link {{ request()->routeIs('profile.*') ? 'sidebar-link-active' : 'text-neutral-400 hover:text-white' }}">
                <span class="font-medium">Settings</span>
                <span class="text-[11px] text-neutral-500 mt-0.5">Profile &amp; credentials</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left text-red-400 hover:text-red-300 hover:bg-red-500/10 border-l-[3px] border-transparent hover:border-red-400">
                    <span class="font-medium">Logout</span>
                    <span class="text-[11px] text-neutral-500 mt-0.5">End session</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<nav x-data="{ open: false }" class="lg:hidden bg-neutral-900 border-b border-neutral-800 px-4 py-3">
    <div class="flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-teal-400">HAHA</a>
        <button @click="open = !open" class="text-neutral-400 hover:text-white">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div :class="{'block': open, 'hidden': !open}" class="hidden pt-4 pb-2 space-y-1">
        <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-sm text-neutral-300 hover:text-white hover:bg-neutral-800 rounded-lg {{ request()->routeIs('dashboard') ? 'text-teal-400 bg-neutral-800' : '' }}">Dashboard</a>
        <a href="{{ route('categories.index') }}" class="block px-3 py-2 text-sm text-neutral-300 hover:text-white hover:bg-neutral-800 rounded-lg {{ request()->routeIs('categories.*') ? 'text-teal-400 bg-neutral-800' : '' }}">Categories</a>
        <a href="{{ route('products.index') }}" class="block px-3 py-2 text-sm text-neutral-300 hover:text-white hover:bg-neutral-800 rounded-lg {{ request()->routeIs('products.*') ? 'text-teal-400 bg-neutral-800' : '' }}">Products</a>
        <a href="{{ route('money-report.index') }}" class="block px-3 py-2 text-sm text-neutral-300 hover:text-white hover:bg-neutral-800 rounded-lg {{ request()->routeIs('money-report.*') ? 'text-teal-400 bg-neutral-800' : '' }}">Money Report</a>
        <hr class="border-neutral-800 my-2">
        <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-neutral-300 hover:text-white hover:bg-neutral-800 rounded-lg {{ request()->routeIs('profile.*') ? 'text-teal-400 bg-neutral-800' : '' }}">Settings</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left px-3 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg">Logout</button>
        </form>
    </div>
</nav>
