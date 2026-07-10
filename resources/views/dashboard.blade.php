<x-app-layout>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Dashboard</h1>
        <p class="text-sm text-neutral-400 mt-1">Welcome back, {{ Auth::user()->name }}. Here's your inventory overview.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="stat-card border-t-teal-400">
            <div class="flex items-center justify-between mb-4">
                <div class="h-10 w-10 rounded-lg bg-teal-500/20 flex items-center justify-center">
                    <svg class="h-5 w-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-white">{{ $totalProducts }}</p>
            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500 mt-1">Total Products</p>
            <p class="text-xs text-neutral-500 mt-1">All items in inventory</p>
            <a href="{{ route('products.index') }}" class="inline-block mt-3 text-xs font-medium text-teal-400 hover:text-teal-300">View all &rarr;</a>
        </div>

        <div class="stat-card border-t-blue-400">
            <div class="flex items-center justify-between mb-4">
                <div class="h-10 w-10 rounded-lg bg-blue-500/20 flex items-center justify-center">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-white">{{ $totalCategories }}</p>
            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500 mt-1">Categories</p>
            <p class="text-xs text-neutral-500 mt-1">Product types</p>
            <a href="{{ route('categories.index') }}" class="inline-block mt-3 text-xs font-medium text-blue-400 hover:text-blue-300">Manage &rarr;</a>
        </div>

        <div class="stat-card border-t-orange-400">
            <div class="flex items-center justify-between mb-4">
                <div class="h-10 w-10 rounded-lg bg-orange-500/20 flex items-center justify-center">
                    <svg class="h-5 w-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-white">{{ $lowStockCount }}</p>
            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500 mt-1">Low Stock</p>
            <p class="text-xs text-neutral-500 mt-1">Items below threshold</p>
            <a href="{{ route('products.index', ['low_stock' => 1]) }}" class="inline-block mt-3 text-xs font-medium text-orange-400 hover:text-orange-300">Review &rarr;</a>
        </div>

        <div class="stat-card border-t-yellow-400">
            <div class="flex items-center justify-between mb-4">
                <div class="h-10 w-10 rounded-lg bg-yellow-500/20 flex items-center justify-center">
                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-white">FRW {{ number_format($inventoryValue, 0) }}</p>
            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-500 mt-1">Inventory Value</p>
            <p class="text-xs text-neutral-500 mt-1">Total cost basis</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 panel">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-neutral-400 mb-4">Recent Products</h3>
            @if($recentProducts->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-xs uppercase tracking-widest text-teal-400 border-b border-neutral-800">
                                <th class="text-left py-3 pr-4 font-semibold">Product</th>
                                <th class="text-left py-3 pr-4 font-semibold">Category</th>
                                <th class="text-left py-3 pr-4 font-semibold">SKU</th>
                                <th class="text-right py-3 pr-4 font-semibold">Stock</th>
                                <th class="text-right py-3 font-semibold">Price</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-800">
                            @foreach($recentProducts as $product)
                            <tr class="text-neutral-300 hover:bg-neutral-800/50">
                                <td class="py-3 pr-4">
                                    <div class="flex items-center gap-3">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="" class="h-8 w-8 rounded object-cover">
                                        @else
                                            <div class="h-8 w-8 rounded bg-neutral-800 flex items-center justify-center text-xs text-neutral-500">N/A</div>
                                        @endif
                                        <span class="font-medium text-white">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 pr-4 text-neutral-400">{{ $product->category->name }}</td>
                                <td class="py-3 pr-4 font-mono text-xs text-neutral-500">{{ $product->sku }}</td>
                                <td class="py-3 pr-4 text-right">
                                    <span class="{{ $product->isLowStock() ? 'text-orange-400' : 'text-green-400' }}">{{ $product->stock_quantity }}</span>
                                </td>
                                <td class="py-3 text-right">FRW {{ number_format($product->selling_price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-neutral-500">No products yet. <a href="{{ route('products.create') }}" class="text-teal-400 hover:text-teal-300">Add your first product</a></p>
            @endif
        </div>

        <div class="panel">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-neutral-400 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('products.create') }}" class="flex flex-col items-center justify-center gap-2 rounded-lg bg-teal-500/10 border border-teal-500/20 p-4 text-teal-400 hover:bg-teal-500/20 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span class="text-xs font-medium">Add Product</span>
                </a>
                <a href="{{ route('categories.create') }}" class="flex flex-col items-center justify-center gap-2 rounded-lg bg-teal-500/10 border border-teal-500/20 p-4 text-teal-400 hover:bg-teal-500/20 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span class="text-xs font-medium">Add Category</span>
                </a>
                <a href="{{ route('products.index') }}" class="flex flex-col items-center justify-center gap-2 rounded-lg bg-teal-500/10 border border-teal-500/20 p-4 text-teal-400 hover:bg-teal-500/20 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span class="text-xs font-medium">Stock Audit</span>
                </a>
                <a href="{{ route('products.index') }}" class="flex flex-col items-center justify-center gap-2 rounded-lg bg-teal-500/10 border border-teal-500/20 p-4 text-teal-400 hover:bg-teal-500/20 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="text-xs font-medium">Reports</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
