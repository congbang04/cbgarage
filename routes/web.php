<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('auth')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('login', [AuthController::class, 'store'])->name('auth.store');
    Route::get('register', [AuthController::class, 'register'])->name('auth.register');
    Route::POST('confirmed-register', [AuthController::class, 'storeRegister'])->name('auth.storeRegister');
    Route::POST('logout', [AuthController::class, 'logout'])->name('auth.logout');
});
