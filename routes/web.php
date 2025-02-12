<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\RecapController;
use App\Http\Controllers\WorkFieldController;
use Illuminate\Support\Facades\Route;

// Route Dashboard - untuk pengguna yang sudah login
Route::get('/', [DashboardController::class, 'dashboard'])
    ->name('dashboard')
    ->middleware('auth');

// Route Login - untuk pengguna yang belum login
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);

// Route Logout - untuk pengguna yang sudah login
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Route CRUD Guest
Route::resource('guest', GuestController::class);
Route::post('/guest/{id}/update-status', [GuestController::class, 'updateStatus'])
    ->name('guest.updateStatus');

// Route Bidang
Route::resource('workfield', WorkFieldController::class);

// Route Rekap Tamu
Route::get('/recap', [RecapController::class, 'index'])
    ->name('guest.recap');
Route::get('/recap/export', [RecapController::class, 'export'])
    ->name('guest.export');
