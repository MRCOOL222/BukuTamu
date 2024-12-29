<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    public function handle($request, Closure $next)
    {
        // Waktu sesi dalam menit
        $timeout = config('session.lifetime') * 60; // default: 15 menit
        $lastActivity = session('last_activity_time', now()->timestamp);

        if (now()->timestamp - $lastActivity > $timeout) {
            // Logout user jika sesi telah berakhir
            Auth::logout();
            session()->flush();

            // Set pesan flash untuk sesi berakhir
            session()->flash('session_expired', 'Sesi Anda telah berakhir. Silakan login kembali.');

            return redirect()->route('login');
        }

        // Perbarui aktivitas terakhir
        session(['last_activity_time' => now()->timestamp]);

        return $next($request);
    }
}

