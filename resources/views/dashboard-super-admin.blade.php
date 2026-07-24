<x-app-layout>
    <div class="mb-6 sm:mb-8">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Super Admin Dashboard</h1>
        <p class="text-xs sm:text-sm text-gray-500 dark:text-neutral-400 mt-1">Welcome back, {{ Auth::user()->name }}. Here's your manager overview.</p>
    </div>

    <div class="flex flex-wrap items-center gap-2 mb-6">
        <a href="{{ route('dashboard', ['period' => 'today']) }}"
           class="px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors {{ $period === 'today' ? 'bg-teal-500 text-white' : 'bg-gray-200 text-gray-600 hover:text-gray-900 hover:bg-gray-300 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-700' }}">
            Today
        </a>
        <a href="{{ route('dashboard', ['period' => 'week']) }}"
           class="px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors {{ $period === 'week' ? 'bg-teal-500 text-white' : 'bg-gray-200 text-gray-600 hover:text-gray-900 hover:bg-gray-300 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-700' }}">
            This Week
        </a>
        <a href="{{ route('dashboard', ['period' => 'month']) }}"
           class="px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors {{ $period === 'month' ? 'bg-teal-500 text-white' : 'bg-gray-200 text-gray-600 hover:text-gray-900 hover:bg-gray-300 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-700' }}">
            This Month
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
        <div class="stat-card border-t-teal-400">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-teal-500/20 flex items-center justify-center">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $totalManagers }}</p>
            <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-neutral-500 mt-1">Total Managers</p>
            <p class="text-[10px] sm:text-xs text-gray-500 dark:text-neutral-500 mt-1">{{ $activeManagers }} active</p>
        </div>

        <div class="stat-card border-t-green-400">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-green-500/20 flex items-center justify-center">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">FRW {{ number_format($totalRevenue, 0) }}</p>
            <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-neutral-500 mt-1">Total Revenue</p>
        </div>

        <div class="stat-card border-t-blue-400">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-blue-500/20 flex items-center justify-center">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalQuantity) }}</p>
            <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-neutral-500 mt-1">Items Sold</p>
        </div>

        <div class="stat-card border-t-purple-400">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-purple-500/20 flex items-center justify-center">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalTransactions) }}</p>
            <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-neutral-500 mt-1">Transactions</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="panel">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-neutral-400 mb-4">Manager Performance</h3>
            @if($managerStats->count())
                <div class="space-y-4">
                    @foreach($managerStats as $stat)
                        <div class="rounded-lg bg-gray-100 border border-gray-200 dark:bg-neutral-800/50 dark:border-neutral-700 p-3 sm:p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 sm:h-10 sm:w-10 rounded-full bg-teal-500/20 flex items-center justify-center text-sm font-semibold text-teal-400">
                                        {{ strtoupper(substr($stat['manager']->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $stat['manager']->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-neutral-500 truncate">{{ $stat['manager']->email }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $stat['manager']->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                    {{ $stat['manager']->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="grid grid-cols-3 gap-2 sm:gap-3">
                                <div>
                                    <p class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">FRW {{ number_format($stat['total_revenue'], 0) }}</p>
                                    <p class="text-[9px] sm:text-[10px] uppercase tracking-wider text-gray-500 dark:text-neutral-500">Revenue</p>
                                </div>
                                <div>
                                    <p class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">{{ number_format($stat['total_quantity']) }}</p>
                                    <p class="text-[9px] sm:text-[10px] uppercase tracking-wider text-gray-500 dark:text-neutral-500">Items Sold</p>
                                </div>
                                <div>
                                    <p class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">{{ $stat['total_transactions'] }}</p>
                                    <p class="text-[9px] sm:text-[10px] uppercase tracking-wider text-gray-500 dark:text-neutral-500">Transactions</p>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-neutral-700 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500 dark:text-neutral-400">
                                <span>Cash: <span class="text-green-400 font-medium">FRW {{ number_format($stat['cash_revenue'], 0) }}</span></span>
                                <span>Momo: <span class="text-blue-400 font-medium">FRW {{ number_format($stat['momo_revenue'], 0) }}</span></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-neutral-500">No managers found. <a href="{{ route('users.create') }}" class="text-teal-400 hover:text-teal-300">Create one</a></p>
            @endif
        </div>

        <div class="panel">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-neutral-400 mb-4">Daily Breakdown</h3>
            @if($dailyBreakdown->count())
                <div class="overflow-x-auto max-h-96 overflow-y-auto">
                    <table class="w-full text-sm">
                        <thead class="sticky top-0 bg-gray-50 dark:bg-neutral-900">
                            <tr class="text-xs uppercase tracking-widest text-teal-400 border-b border-gray-200 dark:border-neutral-800">
                                <th class="text-left py-3 pr-4 font-semibold">Date</th>
                                <th class="text-left py-3 pr-4 font-semibold">Manager</th>
                                <th class="text-right py-3 pr-4 font-semibold">Qty</th>
                                <th class="text-right py-3 pr-4 font-semibold">Revenue</th>
                                <th class="text-right py-3 font-semibold">Sales</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-800">
                            @foreach($dailyBreakdown as $row)
                            <tr class="text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-800/50">
                                <td class="py-3 pr-4 text-gray-500 dark:text-neutral-400">{{ $row->report_date->format('M d, Y') }}</td>
                                <td class="py-3 pr-4">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $row->user->name ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3 pr-4 text-right">{{ number_format($row->quantity) }}</td>
                                <td class="py-3 pr-4 text-right text-green-400 font-medium">FRW {{ number_format($row->revenue, 0) }}</td>
                                <td class="py-3 text-right">{{ $row->transactions }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-neutral-500">No sales data for this period.</p>
            @endif
        </div>
    </div>

    <div class="panel">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-neutral-400 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <a href="{{ route('users.create') }}" class="flex flex-col items-center justify-center gap-2 rounded-lg bg-teal-500/10 border border-teal-500/20 p-3 sm:p-4 text-teal-400 hover:bg-teal-500/20 transition-colors">
                <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                <span class="text-xs font-medium">Add Manager</span>
            </a>
            <a href="{{ route('users.index') }}" class="flex flex-col items-center justify-center gap-2 rounded-lg bg-teal-500/10 border border-teal-500/20 p-3 sm:p-4 text-teal-400 hover:bg-teal-500/20 transition-colors">
                <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-xs font-medium">View Managers</span>
            </a>
            <a href="{{ route('users.index') }}" class="flex flex-col items-center justify-center gap-2 rounded-lg bg-teal-500/10 border border-teal-500/20 p-3 sm:p-4 text-teal-400 hover:bg-teal-500/20 transition-colors">
                <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="text-xs font-medium">Reports</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center gap-2 rounded-lg bg-teal-500/10 border border-teal-500/20 p-3 sm:p-4 text-teal-400 hover:bg-teal-500/20 transition-colors">
                <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-xs font-medium">Settings</span>
            </a>
        </div>
    </div>
</x-app-layout>
