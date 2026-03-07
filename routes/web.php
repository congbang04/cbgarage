<?php

use App\Http\Controllers\SlotController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CustomerController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
    ===============================
    AUTHENTICATION ROUTES
    ===============================
*/

Route::get('/', [CustomerController::class, 'home'])->name('home');
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'store'])->name('store');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::POST('confirmed-register', [AuthController::class, 'storeRegister'])->name('storeRegister');
Route::POST('logout', [AuthController::class, 'logout'])->name('logout');
/*
    ===============================
    EMAIL VERIFICATION ROUTES
    ===============================
*/
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
/*
    ===============================
    CUSTOMER ROUTES
    ===============================
*/
Route::get('/profile', [CustomerController::class, 'profile'])
    ->middleware(['auth', 'verified'])
    ->name('customer.profile');

Route::put('/customer/update', [CustomerController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('customer.update');
/*
    ===============================
    CAR MANAGEMENT ROUTES
    ===============================
*/
Route::prefix('car')->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/{car}', [CarController::class, 'carDetail'])
            ->name('customer.carDetail');
        Route::post('/', [CarController::class, 'store'])->name('car.store');
        Route::put('/{car}', [CarController::class, 'update'])->name('car.update');
        Route::delete('/{car}', [CarController::class, 'destroy'])->name('car.destroy');
    });
/*
    ===============================
    BOOKING ROUTES
    ===============================
*/
Route::prefix('booking')
    ->group(function () {
        Route::get('/', [BookingController::class, 'index'])
            ->name('booking.index');
        // Route::get('/{car}', [CarController::class, 'carDetail'])
        //     ->name('customer.carDetail');
        // Route::post('/', [CarController::class, 'store'])->name('car.store');
        // Route::put('/{car}', [CarController::class, 'update'])->name('car.update');
        // Route::delete('/{car}', [CarController::class, 'destroy'])->name('car.destroy');
    });

Route::resource('branches', BranchController::class);


Route::get('slots', [SlotController::class, 'index'])->name('slots.index');
// Route::post('slots/lock', [SlotController::class, 'storeLock'])->name('slots.storeLock');
// Route::post('slots/confirmed-booking', [SlotController::class, 'confirmedBooking'])->name('slots.confirmedBooking');
