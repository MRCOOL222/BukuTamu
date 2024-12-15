<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route Dashboard - Hanya untuk pengguna yang sudah login
Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard')->middleware('auth');
Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');

// Route Login - Hanya untuk pengguna yang belum login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);

// Route Logout - Hanya untuk pengguna yang sudah login
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route untuk CRUD Guest menggunakan controller dan prefix 'guest'
Route::controller(GuestController::class)->prefix('guest')->middleware('auth')->group(function () {
    Route::get('', 'index')->name('guest.index');          // Menampilkan daftar tamu
    Route::get('create', 'create')->name('guest.create');  // Menampilkan form untuk tambah tamu
    Route::post('store', 'store')->name('guest.store');     // Menyimpan tamu baru
    Route::get('show/{id}', 'show')->name('guest.show');    // Menampilkan detail tamu
    Route::get('edit/{id}', 'edit')->name('guest.edit');    // Menampilkan form untuk edit tamu
    Route::put('edit/{id}', 'update')->name('guest.update'); // Mengupdate tamu
    Route::delete('destroy/{id}', 'destroy')->name('guest.destroy'); // Menghapus tamu
});
