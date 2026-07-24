<x-app-layout>
    <div x-data="{
        lightboxUrl: null,
        openLightbox(url) { this.lightboxUrl = url; },
        closeLightbox() { this.lightboxUrl = null; }
    }" class="space-y-4 sm:space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="min-w-0">
                <h1 class="text-xl sm:text-2xl font-bold text-white truncate">{{ $product->name }}</h1>
                <p class="text-gray-500 text-xs sm:text-sm mt-1">{{ $product->category->name ?? '—' }} &middot; SKU: {{ $product->sku }}</p>
            </div>
            <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                <a href="{{ route('daily-reports.create', $product) }}" class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-400 text-black font-semibold px-3 sm:px-4 py-2 rounded-lg text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span class="hidden sm:inline">Take Report</span>
                    <span class="sm:hidden">Report</span>
                </a>
                <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center gap-2 bg-neutral-800 hover:bg-neutral-700 text-white px-3 sm:px-4 py-2 rounded-lg text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <a href="{{ route('products.index') }}" class="text-sm text-neutral-400 hover:text-neutral-300">Back</a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <div class="lg:col-span-1 space-y-4">
                <div class="panel p-4 sm:p-5 space-y-4">
                    <h2 class="text-sm font-semibold text-white uppercase tracking-wider">Product Image</h2>
                    @if ($product->image_path)
                        <div class="relative overflow-hidden rounded-lg bg-neutral-800 cursor-pointer group"
                             @click="openLightbox('{{ Storage::url($product->image_path) }}')">
                            <img src="{{ Storage::url($product->image_path) }}" alt=""
                                 class="w-full object-cover transition-transform duration-300 group-hover:scale-150">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                <span class="text-white/0 group-hover:text-white/80 text-sm font-medium transition-colors">Click to zoom</span>
                            </div>
                        </div>
                    @else
                        <div class="w-full aspect-square rounded-lg bg-neutral-800 flex items-center justify-center text-neutral-600">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                </div>

                <div class="panel p-4 sm:p-5 space-y-3">
                    <h2 class="text-sm font-semibold text-white uppercase tracking-wider">Details</h2>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-neutral-500 text-xs">Purchase Price</p>
                            <p class="text-white font-medium">FRW {{ number_format($product->purchase_price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-neutral-500 text-xs">Selling Price</p>
                            <p class="text-white font-medium">FRW {{ number_format($product->selling_price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-neutral-500 text-xs">Stock</p>
                            <p class="text-white font-medium">{{ $product->stock_quantity }}</p>
                        </div>
                        <div>
                            <p class="text-neutral-500 text-xs">Low Threshold</p>
                            <p class="text-white font-medium">{{ $product->low_stock_threshold }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-4">
                <div class="panel p-4 sm:p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-semibold text-white uppercase tracking-wider">Sales History</h2>
                        <a href="{{ route('daily-reports.create', $product) }}" class="text-sm text-teal-400 hover:text-teal-300">+ New Report</a>
                    </div>

                    @if ($product->dailyReports->isNotEmpty())
                        {{-- Desktop table --}}
                        <div class="hidden sm:block overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-teal-400 text-xs font-semibold uppercase tracking-widest">
                                        <th class="text-left px-3 py-3">Date</th>
                                        <th class="text-right px-3 py-3">Qty</th>
                                        <th class="text-right px-3 py-3 hidden md:table-cell">Price</th>
                                        <th class="text-right px-3 py-3">Revenue</th>
                                        <th class="text-left px-3 py-3 hidden lg:table-cell">Payment</th>
                                        <th class="text-left px-3 py-3 hidden lg:table-cell">Notes</th>
                                        <th class="text-right px-3 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-800">
                                    @foreach ($product->dailyReports as $report)
                                        <tr class="hover:bg-neutral-800/50">
                                            <td class="px-3 py-3 text-gray-300">{{ $report->report_date->format('M d, Y') }}</td>
                                            <td class="px-3 py-3 text-right text-gray-300">{{ $report->quantity_sold }}</td>
                                            <td class="px-3 py-3 text-right text-gray-300 hidden md:table-cell">FRW {{ number_format($report->selling_price, 2) }}</td>
                                            <td class="px-3 py-3 text-right text-teal-400 font-semibold">FRW {{ number_format($report->total_revenue, 2) }}</td>
                                            <td class="px-3 py-3 text-gray-400 text-xs hidden lg:table-cell">{{ $report->payment_method === 'mobile_money' ? 'Momo' : 'Cash' }}</td>
                                            <td class="px-3 py-3 text-gray-500 max-w-[200px] truncate hidden lg:table-cell">{{ $report->notes ?: '—' }}</td>
                                            <td class="px-3 py-3 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('daily-reports.edit', [$product, $report]) }}" class="text-gray-500 hover:text-teal-400">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    </a>
                                                    <form action="{{ route('daily-reports.destroy', [$product, $report]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this report?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-gray-500 hover:text-red-400">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile card list --}}
                        <div class="sm:hidden space-y-3">
                            @foreach ($product->dailyReports as $report)
                                <div class="p-3 rounded-lg bg-neutral-800/50 border border-neutral-700">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-300">{{ $report->report_date->format('M d, Y') }}</span>
                                        <span class="text-sm font-bold text-teal-400">FRW {{ number_format($report->total_revenue, 2) }}</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-gray-400 mb-2">
                                        <span>Qty: {{ $report->quantity_sold }}</span>
                                        <span>&middot;</span>
                                        <span>FRW {{ number_format($report->selling_price, 2) }}/ea</span>
                                        <span>&middot;</span>
                                        <span>{{ $report->payment_method === 'mobile_money' ? 'Momo' : 'Cash' }}</span>
                                    </div>
                                    @if($report->notes)
                                        <p class="text-xs text-gray-500 mb-2">{{ $report->notes }}</p>
                                    @endif
                                    <div class="flex items-center gap-2 pt-2 border-t border-neutral-700">
                                        <a href="{{ route('daily-reports.edit', [$product, $report]) }}" class="text-xs px-2 py-1 rounded bg-gray-200 dark:bg-neutral-700 text-gray-400">Edit</a>
                                        <form action="{{ route('daily-reports.destroy', [$product, $report]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this report?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs px-2 py-1 rounded bg-red-500/10 text-red-400">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-6 text-sm">No sales reports yet. <a href="{{ route('daily-reports.create', $product) }}" class="text-teal-400 hover:text-teal-300">Take the first report</a>.</p>
                    @endif
                </div>
            </div>
        </div>

        <template x-if="lightboxUrl">
            <div @click="closeLightbox()"
                 class="fixed inset-0 z-[100] flex items-center justify-center bg-black/85 p-4"
                 x-cloak>
                <img :src="lightboxUrl" class="max-h-[95vh] max-w-[95vw] rounded-lg object-contain shadow-2xl"
                     @click.stop
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
                <button @click="closeLightbox()"
                        class="absolute top-4 right-4 h-8 w-8 rounded-full bg-neutral-800/80 text-white flex items-center justify-center hover:bg-neutral-700">&times;</button>
            </div>
        </template>
    </div>
</x-app-layout>
