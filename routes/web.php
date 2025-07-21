<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Items
    Route::get('/items/data', [ItemController::class, 'data'])->name('items.data');
    Route::get('/items/get-price/{id}', [ItemController::class, 'getItemPrice'])->name('items.get-price');
    Route::resource('items', ItemController::class);

    // Sales
    Route::get('/sales/data', [SaleController::class, 'data'])->name('sales.data');
    Route::resource('sales', SaleController::class);

    // Payments
    Route::get('/payments/data', [PaymentController::class, 'data'])->name('payments.data');
    Route::get('/payments/get-sale-details/{saleId}', [PaymentController::class, 'getSaleDetails'])->name('payments.get-sale-details');
    Route::resource('payments', PaymentController::class);

    // Users
    Route::middleware('role:admin')->group(function () {
        Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
        Route::resource('users', UserController::class);
    });
});

require __DIR__ . '/auth.php';
