<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TechnicianJobController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
    ===============================
    RESOURCE ROUTES
    ===============================
*/

Route::resource('branches', BranchController::class);
Route::get('/partial/branch-list', [BranchController::class, 'branchList'])->name('branchList');
Route::resource('services', ServiceController::class);
Route::get('/partial/service-list', [ServiceController::class, 'servicesList'])->name('serviceList');
Route::resource('parts', PartController::class);
Route::get('/partial/part-list', [PartController::class, 'partsList'])->name('partList');
Route::get('/parts-json', [PartController::class, 'partsJson'])->name('partsJson');
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
        Route::get('/', [BookingController::class, 'index'])->name('booking.index');
        Route::post('/', [BookingController::class, 'createBooking'])->name('createBooking');
        Route::post('/create-lock', [BookingController::class, 'createLock'])->name('createLock');
        Route::post('/release-lock', [BookingController::class, 'releaseLock'])->name('releaseLock');
    });

Route::get('slots', [SlotController::class, 'index'])->name('slots.index');
/*
    ===============================
    ADMIN DASHBOARD ROUTES
    ===============================
*/
Route::get('/admin-dashboard', [AdminController::class, 'dashboard'])->name('adminDashboard');
Route::get('/customer-search', [AdminController::class, 'customerSearch'])->name('customerSearch');
/*
    ===============================
    RECEPTION ROUTES
    ===============================
*/
Route::prefix('reception')
    ->group(function () {
        Route::get('/', [ReceptionController::class, 'list'])->name('reception.list');
        Route::get('/partial/table-list', [ReceptionController::class, 'tableList'])->name('table');
        Route::get('/create', [ReceptionController::class, 'create'])->name('reception.create');
        Route::get('/quotation', [ReceptionController::class, 'quotation'])->name('reception.quotation');
        Route::post('/store', [ReceptionController::class, 'storeReception'])->name('reception.store');

        // Quotation API endpoints
        Route::get('/services', [ReceptionController::class, 'getServices'])->name('reception.services');
        Route::get('/parts', [ReceptionController::class, 'getParts'])->name('reception.parts');
        Route::get('/technicians', [ReceptionController::class, 'getTechnicians'])->name('reception.technicians');
        Route::post('/quotation/{orderId}', [ReceptionController::class, 'saveQuotation'])->name('reception.save-quotation');
        Route::get('/quotation/{orderId}/items', [ReceptionController::class, 'getQuotationItems'])->name('reception.quotation-items');
        Route::get('/quotation/{orderId}', [ReceptionController::class, 'showQuotation'])->name('reception.show-quotation');
    });
/*
    ===============================
    TECHNICIAN JOB ROUTES
    ===============================
*/
Route::prefix('technician-jobs')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/', [TechnicianJobController::class, 'jobs'])->name('technician.jobs');
        Route::get('/partial/table-list', [TechnicianJobController::class, 'tableList'])->name('technician.jobs.table');
        Route::get('/execution/{orderId}', [TechnicianJobController::class, 'execution'])->name('technician.jobs.execution');
        Route::put('/execution/{orderId}/status', [TechnicianJobController::class, 'updateStatus'])->name('technician.jobs.updateStatus');
        Route::post('/execution/{orderId}/complete-item', [TechnicianJobController::class, 'completeItem'])->name('technician.jobs.completeItem');
        Route::post('/execution/{orderId}/request-part', [TechnicianJobController::class, 'requestPart'])->name('technician.jobs.requestPart');
    });
/*
    ===============================
    STAFF MANAGEMENT ROUTES (Settings)
    ===============================
*/
Route::prefix('staff')
    ->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('staff.index');
        Route::get('/list', [StaffController::class, 'staffList'])->name('staff.list');
        Route::post('/', [StaffController::class, 'store'])->name('staff.store');
        Route::put('/{staff}', [StaffController::class, 'update'])->name('staff.update');
        Route::delete('/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
        Route::post('/{staff}/toggle-status', [StaffController::class, 'toggleStatus'])->name('staff.toggle-status');
    });

/*
    ===============================
    WAREHOUSE MANAGEMENT ROUTES
    ===============================
*/
Route::prefix('warehouse')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        // Inventory management
        Route::get('/inventory', [WarehouseController::class, 'inventory'])->name('warehouse.inventory');
        Route::get('/inventory-list', [WarehouseController::class, 'inventoryList'])->name('warehouse.inventory-list');
        Route::get('/pending-count', [WarehouseController::class, 'getPendingCount'])->name('warehouse.getPendingCount');


        // Part requests management
        Route::get('/part-requests', [WarehouseController::class, 'partRequests'])->name('warehouse.part-requests');
        Route::get('/card-part-requests', [WarehouseController::class, 'cardPartRequests'])->name('warehouse.card-part-requests');
        Route::get('/goods-issue/{partRequestId}', [WarehouseController::class, 'showGoodsIssue'])->name('warehouse.goods-issue');
        Route::get('/goods-issue-transfer/{stockTransferId}', [WarehouseController::class, 'showTransferGoodsIssue'])->name('warehouse.goods-issue-transfer');
        Route::post('/approve-part-request/{partRequestId}', [WarehouseController::class, 'approvePartRequest'])->name('warehouse.approve-part-request');
        Route::post('/reject-part-request/{partRequestId}', [WarehouseController::class, 'rejectPartRequest'])->name('warehouse.reject-part-request');
        Route::post('/cancel-transfer-request/{stockTransferId}', [WarehouseController::class, 'cancelTransferRequest'])->name('warehouse.cancel-transfer-request');

        // Stock transfer management
        Route::get('/stock-transfer', [WarehouseController::class, 'stockTransfer'])->name('warehouse.stock-transfer');
        Route::get('/stock-transfer-history', [WarehouseController::class, 'stockTransferHistory'])->name('warehouse.stock-transfer-history');
        Route::get('/card-transfer-requests', [WarehouseController::class, 'cardTransferRequests'])->name('warehouse.card-transfer-requests');
        Route::post('/stock-transfer/create', [WarehouseController::class, 'createStockTransfer'])->name('warehouse.create-stock-transfer');
        Route::get('/parts', [WarehouseController::class, 'getSampleParts'])->name('warehouse.get-parts');
        Route::post('/transfer-request/{stockTransferId}/approve', [WarehouseController::class, 'approveTransferRequest'])->name('warehouse.transfer-request.approve');
        Route::get('/transfer-history', [WarehouseController::class, 'fullTransferHistory'])->name('warehouse.transfer.history');
        Route::get('/transfer-history-table', [WarehouseController::class, 'transferHistoryTable'])->name('warehouse.transfer.history-table');
        Route::get('/incoming-transfer', [WarehouseController::class, 'getIncomingTransfers'])->name('warehouse.incoming-transfer');
        Route::post('/complete-transfer-request/{stockTransferId}', [WarehouseController::class, 'completeTransferRequest'])->name('warehouse.complete-transfer-request');
    });

Route::middleware(['auth'])->group(function () {
    // View
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

    // AJAX API endpoints
    Route::prefix('api/chat')->group(function () {
        Route::get('/sessions', [ChatController::class, 'getSessions'])
            ->name('chat.get-sessions');
        Route::post('/session/create', [ChatController::class, 'createSession'])
            ->name('chat.create-session');
        Route::get('/messages/{sessionId}', [ChatController::class, 'getMessages'])
            ->name('chat.get-messages-by-session');
        Route::post('/send', [ChatController::class, 'sendMessage'])
            ->name('chat.send');
    });
});
