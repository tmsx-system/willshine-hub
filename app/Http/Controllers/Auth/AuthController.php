<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('customer')->user();
            
            // Check restrictions
            if (!$user->is_active) {
                Auth::guard('customer')->logout();
                throw ValidationException::withMessages([
                    'email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi admin.',
                ]);
            }

            // Update last login
            $user->update(['last_login_at' => now()]);

            $request->session()->regenerate();

            return redirect()->intended(route('buyer.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
