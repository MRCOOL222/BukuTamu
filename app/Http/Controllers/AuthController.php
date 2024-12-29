<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string', // Validasi username
            'password' => 'required|string', // Validasi password
            'g-recaptcha-response' => 'required',
        ]);

        // Coba login dengan username dan password yang diberikan
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            // Regenerasi session setelah login sukses
            $request->session()->regenerate();

            // Redirect ke halaman yang diinginkan setelah login berhasil
            return redirect()->intended('/');
        }

        // Jika login gagal, beri pesan error
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    // Proses logout
    public function logout(Request $request)
    {
        // Logout dan invalidate session
        Auth::logout();

        // Invalidate session dan regenerate token untuk keamanan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman login setelah logout
        return redirect('/login'); // Bisa juga redirect ke halaman tertentu setelah logout
    }
}
