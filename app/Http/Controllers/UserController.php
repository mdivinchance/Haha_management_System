<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\NoSqlInjection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', new NoSqlInjection],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'manager';
        $validated['is_active'] = true;

        $user = User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created.');
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', new NoSqlInjection],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated.');
    }

    public function deactivate(User $user): RedirectResponse
    {
        if ((int) $user->id === (int) auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        if ($user->isSuperAdmin() && User::where('role', 'super_admin')->where('is_active', true)->count() <= 1) {
            return back()->with('error', 'Cannot deactivate the last active super admin.');
        }

        $user->update(['is_active' => false]);

        return redirect()->route('users.index')->with('success', 'User deactivated.');
    }

    public function activate(User $user): RedirectResponse
    {
        $user->update(['is_active' => true]);

        return redirect()->route('users.index')->with('success', 'User activated.');
    }
}
