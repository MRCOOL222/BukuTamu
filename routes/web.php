<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\RecapController;
use App\Exports\GuestsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route Dashboard - Hanya untuk pengguna yang sudah login
Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard')->middleware('auth');

// Route Login - Hanya untuk pengguna yang belum login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);

// Route Logout - Hanya untuk pengguna yang sudah login
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route untuk CRUD Guest menggunakan controller dan prefix 'guest'
Route::resource('guest', GuestController::class);

// Route Rekap Tamu
Route::get('/recap', [RecapController::class, 'index'])->name('guest.recap');
Route::get('/recap/export', [RecapController::class, 'export'])->name('guest.export');

// Route Export Excel
Route::get('/export-guests', function () {
    return Excel::download(new GuestsExport, 'guests.xlsx');
})->name('guest.export');

