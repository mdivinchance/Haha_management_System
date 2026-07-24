<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-neutral-400">
        Account found. Enter your new password below.
    </div>

    <form method="POST" action="{{ route('password.change') }}">
        @csrf

        <input type="hidden" name="email" value="{{ $email }}">

        <div class="mb-4 p-3 rounded-lg bg-gray-100 dark:bg-neutral-800">
            <p class="text-xs text-gray-500 dark:text-neutral-500">Changing password for</p>
            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $email }}</p>
        </div>

        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">New Password</label>
            <div class="relative">
                <input id="password" type="password" name="password" required minlength="8"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 text-gray-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white px-4 py-2.5 placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/30 focus:outline-none transition-colors text-sm pr-10"
                    placeholder="••••••••">
                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:text-neutral-500 dark:hover:text-neutral-300">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
            @error('password') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        <div class="mt-4" x-data="{ show: false }">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Confirm New Password</label>
            <div class="relative">
                <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 text-gray-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white px-4 py-2.5 placeholder-gray-500 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/30 focus:outline-none transition-colors text-sm pr-10"
                    placeholder="••••••••">
                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:text-neutral-500 dark:hover:text-neutral-300">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
            @error('password_confirmation') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('password.request') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                Use different email
            </a>
            <button type="submit" class="rounded-lg bg-teal-500 hover:bg-teal-400 text-black font-semibold px-6 py-2.5 text-sm transition-colors">
                Change Password
            </button>
        </div>
    </form>
</x-guest-layout>
