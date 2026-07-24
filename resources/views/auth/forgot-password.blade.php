<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-neutral-400">
        Enter your email address and we'll check your account.
    </div>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 text-gray-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white px-4 py-2.5 placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/30 focus:outline-none transition-colors text-sm">
            @error('email') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                Back to login
            </a>
            <button type="submit" class="rounded-lg bg-teal-500 hover:bg-teal-400 text-black font-semibold px-6 py-2.5 text-sm transition-colors">
                Check Email
            </button>
        </div>
    </form>
</x-guest-layout>
