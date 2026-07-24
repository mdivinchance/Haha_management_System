<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with this email address.']);
        }

        return redirect()->route('password.change-form')->with('email', $user->email);
    }
}
