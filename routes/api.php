<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FlowerController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;


Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});

Route::get('flowers', [FlowerController::class, 'index']);
Route::get('flowers/{id}', [FlowerController::class, 'show']);
Route::get('orders', [OrderController::class, 'index']); 
Route::post('orders', [OrderController::class, 'store']); 
Route::get('reviews', [ReviewController::class, 'index']); 
Route::post('reviews', [ReviewController::class, 'store']); 

Route::middleware('auth:api')->group(function () {

    Route::post('flowers', [FlowerController::class, 'store']);
    Route::put('flowers/{id}', [FlowerController::class, 'update']);
    Route::delete('flowers/{id}', [FlowerController::class, 'destroy']);

    Route::put('orders/{id}/status', [OrderController::class, 'updateStatus']);
    Route::delete('orders/{id}', [OrderController::class, 'destroy']);
});

