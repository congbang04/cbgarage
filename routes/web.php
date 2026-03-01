<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CustomerController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [CustomerController::class, 'home'])->name('home');
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'store'])->name('store');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::POST('confirmed-register', [AuthController::class, 'storeRegister'])->name('storeRegister');
Route::POST('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/email/verify', function () {
    if (auth()->user()->hasVerifiedEmail()) {
        return redirect()->route('home');
    }
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/profile', [CustomerController::class, 'profile'])->middleware(['auth', 'verified'])->name('customer.profile');

Route::prefix('car')->middleware(['auth', 'verified'])
    ->group(function () {
        // Route::get('/', [CarController::class, 'index'])->name('car.index');
        Route::post('/', [CarController::class, 'store'])->name('car.store');
    });
Route::get('/car-detail/{car}', [CustomerController::class, 'carDetail'])->middleware(['auth', 'verified'])->name('customer.carDetail');

Route::put('/customer/update', [CustomerController::class, 'update'])->middleware(['auth', 'verified'])->name('customer.update');
