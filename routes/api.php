<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FlowerController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Endpoint Autentikasi (Publik)
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});

// Endpoint Publik (User bisa akses tanpa login)
Route::get('flowers', [FlowerController::class, 'index']);
Route::get('flowers/{id}', [FlowerController::class, 'show']);
Route::get('orders', [OrderController::class, 'index']); // Pindahkan ke sini supaya User bisa lacak
Route::post('orders', [OrderController::class, 'store']); // User bisa pesan sebagai guest
Route::get('reviews', [ReviewController::class, 'index']); // User bisa lihat ulasan di katalog
Route::post('reviews', [ReviewController::class, 'store']); // User bisa kirim ulasan

// Resource Terproteksi JWT (Wajib Login & Kirim Token)
Route::middleware('auth:api')->group(function () {
    // Admin CRUD Flowers
    Route::post('flowers', [FlowerController::class, 'store']);
    Route::put('flowers/{id}', [FlowerController::class, 'update']);
    Route::delete('flowers/{id}', [FlowerController::class, 'destroy']);

    // Admin Manage Orders
    // Route::get('orders', [OrderController::class, 'index']); // Pindah ke atas
    Route::put('orders/{id}/status', [OrderController::class, 'updateStatus']);
    Route::delete('orders/{id}', [OrderController::class, 'destroy']);
});

