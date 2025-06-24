<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Auth::routes();

// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Only Routes
    Route::middleware(['check.role:admin'])->group(function () {

        // User Management
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        Route::get('api/users', [UserController::class, 'getData'])->name('users.data');

        // Items Management (Admin can CRUD)
        Route::resource('items', ItemController::class);
        Route::get('api/items', [ItemController::class, 'getData'])->name('items.data');

        // Request Management (Admin can approve/reject)
        Route::get('requests/pending', [RequestController::class, 'pending'])->name('requests.pending');
        Route::patch('requests/{request}/approve', [RequestController::class, 'approve'])->name('requests.approve');
        Route::patch('requests/{request}/reject', [RequestController::class, 'reject'])->name('requests.reject');

        // Stock Movements (Admin can view all)
        Route::get('stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
        Route::get('stock-movements/{movement}', [StockMovementController::class, 'show'])->name('stock-movements.show');

        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
        Route::get('reports/requests', [ReportController::class, 'requests'])->name('reports.requests');

    });

    // User Routes (Both admin and user can access)
    Route::middleware([])->group(function () {

        // Items (Read only for users)
        Route::get('items', [ItemController::class, 'index'])->name('items.index');
        Route::get('items/{item}', [ItemController::class, 'show'])->name('items.show');

        // Request Routes (Users can create and view their own)
        Route::resource('requests', RequestController::class)->except(['destroy']);
        Route::get('my-requests', [RequestController::class, 'myRequests'])->name('requests.my');

        // Profile Management
        Route::get('profile', [UserController::class, 'profile'])->name('profile.show');
        Route::put('profile', [UserController::class, 'updateProfile'])->name('profile.update');
        Route::put('profile/password', [UserController::class, 'updatePassword'])->name('profile.password');

    });

    // API Routes for AJAX
    Route::prefix('api')->group(function () {
        Route::get('items/search', [ItemController::class, 'search'])->name('api.items.search');
        Route::get('items/{item}/stock', [ItemController::class, 'getStock'])->name('api.items.stock');
        Route::get('dashboard/stats', [DashboardController::class, 'getStats'])->name('api.dashboard.stats');
    });

});
