<x-app-layout>
    <div class="max-w-2xl space-y-6">
        <div class="mb-2">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Settings</h1>
            <p class="text-gray-500 text-sm mt-1">Manage your profile and credentials</p>
        </div>

        <div class="panel">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="panel">
            @include('profile.partials.update-password-form')
        </div>

        <div class="panel border border-red-500/20">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
