<x-app-layout>
    <div class="p-6 space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Money Report</h1>
            <p class="text-gray-500 text-sm mt-1">Daily revenue and sales summary</p>
        </div>

        <form method="GET" action="{{ route('money-report.index') }}" class="panel p-4">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label for="date_from" class="form-label">From</label>
                    <input id="date_from" type="date" name="date_from" value="{{ request('date_from') }}" class="input-field">
                </div>
                <div>
                    <label for="date_to" class="form-label">To</label>
                    <input id="date_to" type="date" name="date_to" value="{{ request('date_to') }}" class="input-field">
                </div>
                <div>
                    <label for="product_id" class="form-label">Product</label>
                    <select id="product_id" name="product_id" class="input-field">
                        <option value="">All Products</option>
                        @foreach ($products as $p)
                            <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">Filter</button>
                </div>
            </div>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="stat-card border-t-2 border-teal-500">
                <span class="stat-label">Total Money Worked</span>
                <span class="stat-value text-teal-400">FRW {{ number_format($totals->total_revenue, 2) }}</span>
            </div>
            <div class="stat-card border-t-2 border-blue-500">
                <span class="stat-label">Total Items Sold</span>
                <span class="stat-value text-blue-400">{{ $totals->total_quantity }}</span>
            </div>
            <div class="stat-card border-t-2 border-purple-500">
                <span class="stat-label">Total Report Entries</span>
                <span class="stat-value text-purple-400">{{ $totals->report_count }}</span>
            </div>
        </div>

        @if ($dailySummary->isNotEmpty())
            <div class="panel overflow-hidden border-t-2 border-teal-500">
                <div class="px-5 py-4 border-b border-neutral-800 bg-neutral-900">
                    <h2 class="text-base font-bold text-teal-400">Money Worked Per Day</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-teal-400 text-xs font-semibold uppercase tracking-widest">
                                <th class="text-left px-5 py-3">Date</th>
                                <th class="text-right px-5 py-3">Items Sold</th>
                                <th class="text-right px-5 py-3">Reports</th>
                                <th class="text-right px-5 py-3">Total Money Worked</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-800">
                            @foreach ($dailySummary as $day)
                                <tr class="hover:bg-neutral-800/50">
                                    <td class="px-5 py-3 text-white font-medium">{{ $day->date->format('M d, Y') }}</td>
                                    <td class="px-5 py-3 text-right text-gray-300">{{ $day->total_quantity }}</td>
                                    <td class="px-5 py-3 text-right text-gray-400">{{ $day->count }}</td>
                                    <td class="px-5 py-3 text-right text-teal-400 font-bold text-base">FRW {{ number_format($day->total_revenue, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel overflow-hidden">
                <div class="px-5 py-3 border-b border-neutral-800">
                    <h2 class="text-sm font-semibold text-white">All Report Details</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-teal-400 text-xs font-semibold uppercase tracking-widest">
                                <th class="text-left px-5 py-3">Date</th>
                                <th class="text-left px-5 py-3">Product</th>
                                <th class="text-right px-5 py-3">Qty</th>
                                <th class="text-right px-5 py-3">Price</th>
                                <th class="text-right px-5 py-3">Revenue</th>
                                <th class="text-left px-5 py-3">Payment</th>
                                <th class="text-left px-5 py-3">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-800">
                            @forelse ($reports as $report)
                                <tr class="hover:bg-neutral-800/50">
                                    <td class="px-5 py-3 text-gray-300">{{ $report->report_date->format('M d, Y') }}</td>
                                    <td class="px-5 py-3">
                                        <a href="{{ route('products.show', $report->product) }}" class="text-white font-medium hover:text-teal-400">{{ $report->product->name }}</a>
                                    </td>
                                    <td class="px-5 py-3 text-right text-gray-300">{{ $report->quantity_sold }}</td>
                                    <td class="px-5 py-3 text-right text-gray-300">FRW {{ number_format($report->selling_price, 2) }}</td>
                                    <td class="px-5 py-3 text-right text-teal-400 font-semibold">FRW {{ number_format($report->total_revenue, 2) }}</td>
                                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $report->payment_method === 'mobile_money' ? 'Momo' : 'Cash' }}</td>
                                    <td class="px-5 py-3 text-gray-500 max-w-xs truncate">{{ $report->notes ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-8 text-center text-gray-500">No reports found matching your filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="panel p-8 text-center text-gray-500">
                <p>No daily reports yet. Start by taking a report on a product.</p>
                <a href="{{ route('products.index') }}" class="text-teal-400 hover:text-teal-300 mt-2 inline-block">Go to Products</a>
            </div>
        @endif
    </div>
</x-app-layout>
