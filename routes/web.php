<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlowerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [FlowerController::class, 'index']);

Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::get('/register', function () { return view('register'); });

// Ganti rute dashboard agar konsisten dengan redirect di login.blade.php
Route::get('/admin/dashboard', function () { 
    return view('admin.dashboard'); 
});

Route::get('/dashboard', function () { 
    return redirect('/admin/dashboard');
});

// Halaman Baru: Pesanan Saya
Route::get('/pesanan', function () { return view('pesanan'); });

// Halaman Baru: Keranjang
Route::get('/keranjang', function () { return view('keranjang'); });
