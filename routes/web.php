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
Route::resource('guest', GuestController::class);
