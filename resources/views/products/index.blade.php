<x-app-layout>
    <div x-data="{
        viewMode: localStorage.getItem('productViewMode') || 'table',
        lightboxUrl: null,
        toggleView() {
            this.viewMode = this.viewMode === 'table' ? 'grid' : 'table';
            localStorage.setItem('productViewMode', this.viewMode);
        },
        openLightbox(url) { this.lightboxUrl = url; },
        closeLightbox() { this.lightboxUrl = null; }
    }" class="space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Products</h1>
                <p class="text-gray-500 text-sm mt-1">Manage your inventory and stock</p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="toggleView" class="text-sm text-neutral-400 hover:text-white transition-colors" :title="viewMode === 'table' ? 'Switch to grid view' : 'Switch to table view'">
                    <template x-if="viewMode === 'table'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </template>
                    <template x-if="viewMode === 'grid'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </template>
                </button>
                <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-400 text-black font-semibold px-4 py-2 rounded-lg text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    New Product
                </a>
            </div>
        </div>

        <div x-show="viewMode === 'table'" class="panel overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-teal-400 text-xs font-semibold uppercase tracking-widest">
                            <th class="text-left px-5 py-3">Product</th>
                            <th class="text-left px-5 py-3">Category</th>
                            <th class="text-left px-5 py-3">SKU</th>
                            <th class="text-right px-5 py-3">Price</th>
                            <th class="text-right px-5 py-3">Stock</th>
                            <th class="text-right px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-800">
                        @forelse ($products as $product)
                            <tr class="hover:bg-neutral-800/50">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($product->image_path)
                                            <div class="group relative overflow-hidden rounded-lg w-10 h-10 flex-shrink-0 cursor-pointer"
                                                 @click="openLightbox('{{ Storage::url($product->image_path) }}')"
                                                 title="Click to zoom">
                                                <img src="{{ Storage::url($product->image_path) }}" alt=""
                                                     class="w-10 h-10 object-cover transition-transform duration-200 group-hover:scale-[3] group-hover:z-10">
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-neutral-800 flex items-center justify-center text-gray-500 flex-shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-white font-medium">{{ $product->name }}</div>
                                            <div class="text-xs text-gray-600">{{ Str::limit($product->description, 40) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-gray-400">{{ $product->category->name ?? '—' }}</td>
                                <td class="px-5 py-3 text-gray-400 font-mono text-xs">{{ $product->sku }}</td>
                                <td class="px-5 py-3 text-right text-gray-300">FRW {{ number_format($product->selling_price, 2) }}</td>
                                <td class="px-5 py-3 text-right">
                                    <span class="{{ $product->isLowStock() ? 'text-orange-400 font-semibold' : ($product->stock_quantity == 0 ? 'text-red-400 font-semibold' : 'text-gray-300') }}">
                                        {{ $product->stock_quantity }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('products.adjust-stock', $product) }}" method="POST" class="inline-flex items-center gap-1">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="change" value="-1">
                                            <button type="submit" class="text-gray-500 hover:text-orange-400 p-1" title="Decrease stock">−</button>
                                        </form>
                                        <span class="text-xs text-gray-600 w-4 text-center font-mono">{{ $product->stock_quantity }}</span>
                                        <form action="{{ route('products.adjust-stock', $product) }}" method="POST" class="inline-flex items-center gap-1">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="change" value="1">
                                            <button type="submit" class="text-gray-500 hover:text-teal-400 p-1" title="Increase stock">+</button>
                                        </form>
                                        <a href="{{ route('products.show', $product) }}" class="text-gray-500 hover:text-teal-400" title="Take Report">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </a>
                                        <a href="{{ route('products.edit', $product) }}" class="text-gray-500 hover:text-teal-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-500 hover:text-red-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-gray-500">No products yet. <a href="{{ route('products.create') }}" class="text-teal-400 hover:text-teal-300">Add your first product</a></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="viewMode === 'grid'" x-cloak class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @forelse ($products as $product)
                <div class="panel overflow-hidden group cursor-pointer" @click="openLightbox('{{ $product->image_path ? Storage::url($product->image_path) : '' }}')">
                    <div class="relative overflow-hidden rounded-lg mb-3 bg-neutral-800 aspect-square">
                        @if ($product->image_path)
                            <img src="{{ Storage::url($product->image_path) }}" alt=""
                                 class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-150">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-neutral-600">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
                    </div>
                    <p class="text-sm font-medium text-white truncate">{{ $product->name }}</p>
                    <p class="text-xs text-neutral-500 mt-0.5">FRW {{ number_format($product->selling_price, 2) }}</p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-xs {{ $product->isLowStock() ? 'text-orange-400' : ($product->stock_quantity == 0 ? 'text-red-400' : 'text-green-400') }}">
                            {{ $product->stock_quantity }} in stock
                        </span>
                        <div class="flex items-center gap-1" @click.stop>
                            <a href="{{ route('products.show', $product) }}" class="text-neutral-500 hover:text-teal-400 p-1 text-xs" title="Take Report">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </a>
                            <form action="{{ route('products.adjust-stock', $product) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="change" value="-1">
                                <button type="submit" class="text-neutral-500 hover:text-orange-400 text-sm px-1">−</button>
                            </form>
                            <form action="{{ route('products.adjust-stock', $product) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="change" value="1">
                                <button type="submit" class="text-neutral-500 hover:text-teal-400 text-sm px-1">+</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">No products yet.</div>
            @endforelse
        </div>

        <template x-if="lightboxUrl">
            <div @click="closeLightbox()"
                 class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 p-4 cursor-pointer"
                 x-cloak>
                <img :src="lightboxUrl" class="max-h-[90vh] max-w-[90vw] rounded-lg object-contain shadow-2xl cursor-default"
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
