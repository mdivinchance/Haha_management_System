<x-app-layout>
    <div class="max-w-2xl">
        <div class="mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Create Category</h1>
            <p class="text-gray-500 text-sm mt-1">Add a new product type</p>
        </div>

        <form method="POST" action="{{ route('categories.store') }}" class="bg-gray-100 dark:bg-neutral-900 rounded-xl p-6 space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 text-gray-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white px-4 py-2.5 placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/30 focus:outline-none transition-colors text-sm">
                @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Description</label>
                <textarea id="description" name="description" rows="3"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 text-gray-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white px-4 py-2.5 placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/30 focus:outline-none transition-colors text-sm">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-teal-500 hover:bg-teal-400 text-black font-semibold px-6 py-2.5 text-sm transition-colors">Create</button>
                <a href="{{ route('categories.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 text-sm">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
