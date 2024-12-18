<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Menampilkan dashboard dengan grafik jumlah tamu berdasarkan tanggal
    public function dashboard()
    {
        // Mengambil jumlah tamu per tanggal
        $guests = Guest::selectRaw('tanggal, count(*) as total')
                       ->groupBy('tanggal')
                       ->orderBy('tanggal')
                       ->get();
        

        // Mengirim data tamu berdasarkan tanggal ke view
        return view('dashboard', compact('guests'));
    }

    public function profile()
    {
        return view('profile'); // Misalnya, jika Anda memiliki halaman profil
    }
}
