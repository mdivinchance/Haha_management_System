<x-app-layout>
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Users</h1>
                <p class="text-gray-500 text-sm mt-1">Manage system users</p>
            </div>
            <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-400 text-black font-semibold px-4 py-2 rounded-lg text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                New User
            </a>
        </div>

        <div class="bg-gray-100 dark:bg-neutral-900 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-teal-400 text-xs font-semibold uppercase tracking-widest">
                            <th class="text-left px-5 py-3">Name</th>
                            <th class="text-left px-5 py-3">Email</th>
                            <th class="text-center px-5 py-3">Role</th>
                            <th class="text-center px-5 py-3">Status</th>
                            <th class="text-right px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-800">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-100 dark:hover:bg-neutral-800/50">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-full bg-teal-500/20 flex items-center justify-center text-sm font-semibold text-teal-400 flex-shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-gray-900 dark:text-white font-medium">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                                <td class="px-5 py-3 text-center">
                                    @if($user->isSuperAdmin())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-500/20 text-purple-400">Super Admin</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-400">Manager</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @if($user->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-500/20 text-green-400">Active</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-500/20 text-red-400">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('users.edit', $user) }}" class="text-gray-500 hover:text-teal-500 dark:text-gray-400 dark:hover:text-teal-400 text-xs font-medium mr-3">Edit</a>
                                    @if((int) $user->id !== (int) auth()->id())
                                        @if($user->is_active)
                                            <form action="{{ route('users.deactivate', $user) }}" method="POST" class="inline" onsubmit="return confirm('Deactivate this user?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-gray-500 hover:text-red-500 dark:text-gray-400 dark:hover:text-red-400 text-xs font-medium">Deactivate</button>
                                            </form>
                                        @else
                                            <form action="{{ route('users.activate', $user) }}" method="POST" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="text-gray-500 hover:text-green-500 dark:text-gray-400 dark:hover:text-green-400 text-xs font-medium">Activate</button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-gray-500">No users yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
