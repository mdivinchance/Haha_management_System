<x-app-layout>
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Categories</h1>
                <p class="text-gray-500 text-sm mt-1">Manage your product types</p>
            </div>
            <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-400 text-black font-semibold px-4 py-2 rounded-lg text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                New Category
            </a>
        </div>

        <div class="bg-neutral-900 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-teal-400 text-xs font-semibold uppercase tracking-widest">
                            <th class="text-left px-5 py-3">Name</th>
                            <th class="text-left px-5 py-3">Description</th>
                            <th class="text-center px-5 py-3">Products</th>
                            <th class="text-right px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-800">
                        @forelse ($categories as $category)
                            <tr class="hover:bg-neutral-800/50">
                                <td class="px-5 py-3 text-white font-medium">{{ $category->name }}</td>
                                <td class="px-5 py-3 text-gray-400">{{ $category->description ?? '—' }}</td>
                                <td class="px-5 py-3 text-center text-gray-300">{{ $category->products_count }}</td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('categories.edit', $category) }}" class="text-gray-400 hover:text-teal-400 text-xs font-medium mr-3">Edit</a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-400 text-xs font-medium">Delete</button>
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
        </div>
    </div>
</x-app-layout>
