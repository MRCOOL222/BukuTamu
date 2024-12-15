<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string', // Validasi username
            'password' => 'required|string',
        ]);

        // Auth::attempt sudah otomatis melakukan pengecekan berdasarkan username dan password
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            // Regenerasi session setelah login sukses
            $request->session()->regenerate();

            // Redirect ke halaman yang diinginkan
            return redirect()->intended('/');
        }

        // Jika login gagal, beri error
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        // Logout dan invalidate session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman login
        return redirect('/login');
    }
}

