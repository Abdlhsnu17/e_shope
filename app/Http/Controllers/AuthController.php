<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        try {
            $berhasil = Auth::attempt($credentials, $request->boolean('remember'));
        } catch (RuntimeException) {
            // Kata sandi tersimpan bukan hash bcrypt — biasanya karena baris user
            // disunting langsung lewat klien database. Jangan sampai jadi error 500.
            throw ValidationException::withMessages([
                'email' => 'Kata sandi akun ini tersimpan dalam format yang tidak valid. '
                    .'Minta administrator menyetel ulang kata sandi melalui menu Pengguna.',
            ]);
        }

        if (! $berhasil) {
            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi tidak sesuai.',
            ]);
        }

        if (! Auth::user()->is_active) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Akun Anda dinonaktifkan. Hubungi administrator.',
            ]);
        }

        $request->session()->regenerate();
        Auth::user()->forceFill(['last_login_at' => now()])->save();

        return redirect()->intended(Auth::user()->isAdmin() ? route('admin.dashboard') : route('home'))
            ->with('success', 'Selamat datang kembali, '.Auth::user()->name.'!');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'unique:users,email'], 'phone' => ['nullable', 'string', 'max:30'], 'password' => ['required', 'confirmed', 'min:8']]);
        $user = User::create(['name' => $data['name'], 'email' => $data['email'], 'telepon' => $data['phone'] ?? null, 'password' => Hash::make($data['password']), 'role' => 'customer', 'is_active' => true]);
        Auth::login($user); $request->session()->regenerate();
        return redirect()->route('home')->with('success', 'Akun berhasil dibuat. Selamat berbelanja!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah keluar.');
    }
}
