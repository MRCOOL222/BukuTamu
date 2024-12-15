<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('dashboard'); // Menampilkan dashboard
    }

    public function profile()
    {
        return view('profile'); // Menampilkan profile
    }
}