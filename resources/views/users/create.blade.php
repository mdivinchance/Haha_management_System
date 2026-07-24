<x-app-layout>
    <div class="max-w-2xl">
        <div class="mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Create User</h1>
            <p class="text-gray-500 text-sm mt-1">Add a new manager to the system</p>
        </div>

        <form method="POST" action="{{ route('users.store') }}" class="bg-gray-100 dark:bg-neutral-900 rounded-xl p-6 space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 text-gray-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white px-4 py-2.5 placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/30 focus:outline-none transition-colors text-sm">
                @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 text-gray-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white px-4 py-2.5 placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/30 focus:outline-none transition-colors text-sm">
                @error('email') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div x-data="{ show: false }">
                <label for="password" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required minlength="8"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 text-gray-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white px-4 py-2.5 placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/30 focus:outline-none transition-colors text-sm pr-10">
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:text-neutral-500 dark:hover:text-neutral-300">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
                @error('password') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div x-data="{ show: false }">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Confirm Password</label>
                <div class="relative">
                    <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 text-gray-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white px-4 py-2.5 placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/30 focus:outline-none transition-colors text-sm pr-10">
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:text-neutral-500 dark:hover:text-neutral-300">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
                @error('password_confirmation') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-teal-500 hover:bg-teal-400 text-black font-semibold px-6 py-2.5 text-sm transition-colors">Create</button>
                <a href="{{ route('users.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 text-sm">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
