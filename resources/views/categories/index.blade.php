<x-app-layout>
    <div class="space-y-4 sm:space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Categories</h1>
                <p class="text-gray-500 text-xs sm:text-sm mt-1">Manage your product types</p>
            </div>
            <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-400 text-black font-semibold px-3 sm:px-4 py-2 rounded-lg text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <span class="hidden sm:inline">New Category</span>
                <span class="sm:hidden">New</span>
            </a>
        </div>

        <div class="bg-gray-100 dark:bg-neutral-900 rounded-xl overflow-hidden">
            {{-- Desktop table --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-teal-400 text-xs font-semibold uppercase tracking-widest">
                            <th class="text-left px-5 py-3">Name</th>
                            <th class="text-left px-5 py-3 hidden md:table-cell">Description</th>
                            <th class="text-center px-5 py-3">Products</th>
                            <th class="text-right px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-800">
                        @forelse ($categories as $category)
                            <tr class="hover:bg-gray-100 dark:hover:bg-neutral-800/50">
                                <td class="px-5 py-3 text-gray-900 dark:text-white font-medium">{{ $category->name }}</td>
                                <td class="px-5 py-3 text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ $category->description ?? '—' }}</td>
                                <td class="px-5 py-3 text-center text-gray-700 dark:text-gray-300">{{ $category->products_count }}</td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('categories.edit', $category) }}" class="text-gray-500 hover:text-teal-500 dark:text-gray-400 dark:hover:text-teal-400 text-xs font-medium mr-3">Edit</a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-500 dark:text-gray-400 dark:hover:text-red-400 text-xs font-medium">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-gray-500">No categories yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile card list --}}
            <div class="sm:hidden divide-y divide-gray-200 dark:divide-neutral-800">
                @forelse ($categories as $category)
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $category->name }}</p>
                                @if($category->description)
                                    <p class="text-xs text-gray-500 dark:text-neutral-500 mt-0.5">{{ Str::limit($category->description, 50) }}</p>
                                @endif
                            </div>
                            <span class="text-xs font-semibold text-teal-400 bg-teal-500/10 px-2 py-0.5 rounded-full">{{ $category->products_count }} products</span>
                        </div>
                        <div class="flex items-center gap-3 mt-2">
                            <a href="{{ route('categories.edit', $category) }}" class="text-xs px-3 py-1.5 rounded-lg bg-gray-200 dark:bg-neutral-800 text-gray-600 dark:text-neutral-400 font-medium">Edit</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs px-3 py-1.5 rounded-lg bg-red-500/10 text-red-400 font-medium">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">No categories yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
