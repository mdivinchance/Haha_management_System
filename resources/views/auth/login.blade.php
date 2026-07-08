<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="admin@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-neutral-600 bg-neutral-800 text-teal-500 focus:ring-teal-500 focus:ring-offset-0" name="remember">
                <span class="ms-2 text-sm text-neutral-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    @if(app()->environment('local'))
        <div class="mt-6 rounded-lg border border-neutral-700 bg-neutral-800/50 p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400">Demo Credentials</p>
            <p class="mt-1 text-sm text-neutral-300">Email: <span class="font-mono text-teal-400">admin@haha.test</span></p>
            <p class="text-sm text-neutral-300">Password: <span class="font-mono text-teal-400">password</span></p>
        </div>
    @endif
</x-guest-layout>
