<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    public function showChangeForm(Request $request): View
    {
        $email = session('email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password', ['email' => $email]);
    }

    public function changePassword(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with this email address.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Password changed successfully. You can now log in.');
    }
}
