<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\ProdukRequestController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Auth::routes(['register' => false, 'reset' => false, 'confirm' => false]);

// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::resource('users', UserController::class)->except(['show']);
    Route::patch('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');

    // 🔧 Optional: For AJAX/API use
    Route::get('/api/users', [UserController::class, 'getData'])->name('users.api');

    // Items - Add these routes
    Route::resource('items', ItemController::class);
    Route::patch('/items/{item}/activate', [ItemController::class, 'activate'])->name('items.activate');
    Route::get('/items/{item}/print-barcode', [ItemController::class, 'printBarcode'])->name('items.print-barcode');
    Route::get('/items/search-by-barcode/{code}', [ItemController::class, 'searchByBarcode'])->name('items.search-by-barcode');

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Bidang (Fields)
    Route::resource('fields', FieldController::class)->except(['show']);

    // Satuan (Units)
    Route::resource('units', UnitController::class)->except(['show']);

    // Produk Request Routes
    Route::get('/produk-request/print/{produkRequest}', [ProdukRequestController::class, 'print'])->name('produk-request.print');
    Route::resource('produk-request', ProdukRequestController::class)->except(['show']);
    Route::prefix('produk-request')->name('produk-request.')->group(function () {
        Route::match(['PUT', 'PATCH'], '/{produkRequest}/update-status', [ProdukRequestController::class, 'updateStatus'])->name('update-status');
    });
    Route::get('/history', [HistoryController::class, 'index'])->name('produk-request.history');

    // Warehouse
    Route::resource('warehouses', WarehouseController::class)->except(['show']);
    Route::resource('pemesanan', PemesananController::class)->except(['show']);
    Route::prefix('pemesanan')->name('pemesanan.')->group(function () {
        Route::match(['PUT', 'PATCH'], '/{pemesanan}/update-status', [PemesananController::class, 'updateStatus'])->name('update-status');
    });
    Route::get('/history/pemesanan', [HistoryController::class, 'pemesanan'])->name('pemesanan.history');
    Route::resource('suppliers', SupplierController::class)->except(['show']);

    // 🔧 Optional: For AJAX/API use and barcode generation
    Route::get('/api/items', [ItemController::class, 'apiSearch'])->name('items.api');
    Route::get('/items/{item}/barcode', [ItemController::class, 'generateBarcode'])->name('items.barcode');
    Route::post('/items/check-code', [ItemController::class, 'checkCode'])->name('items.check-code');
});
