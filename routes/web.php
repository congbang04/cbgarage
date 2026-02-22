<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::prefix('auth')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('login', [AuthController::class, 'store'])->name('auth.store');
    Route::get('register', [AuthController::class, 'register'])->name('auth.register');
    Route::POST('confirmed-register', [AuthController::class, 'storeRegister'])->name('auth.storeRegister');
    Route::POST('logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::get('/email/verify', function () {
    if (auth()->user()->hasVerifiedEmail()) {
        return redirect()->route('welcome');
    }
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/profile', function () {
    // Only verified users may access this route...
})->middleware(['auth', 'verified']);
