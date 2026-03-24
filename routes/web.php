<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\DashboardController;

use App\Http\Controllers\UnitController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialStockController;
use App\Http\Controllers\MaterialComponentController;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductBomController;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;

/**
 * Public routes
 */
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

/**
 * Authenticated area
 */
Route::middleware(['auth'])->group(function () {

    /**
     * PROFILE
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    /**
     * DASHBOARD
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('permission:dashboard.view');

    /**
     * NOTIFICATIONS
     */
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])
        ->name('notifications.markRead');

    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])
        ->name('notifications.markAllRead');

    /**
     * UNITS
     */
    Route::middleware(['permission:units.view'])->group(function () {
        Route::get('/units', [UnitController::class, 'index'])->name('units.index');
        Route::get('/units/create', [UnitController::class, 'create'])->name('units.create')->middleware('permission:units.create');
        Route::post('/units', [UnitController::class, 'store'])->name('units.store')->middleware('permission:units.create');
        Route::get('/units/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit')->middleware('permission:units.update');
        Route::put('/units/{unit}', [UnitController::class, 'update'])->name('units.update')->middleware('permission:units.update');
        Route::delete('/units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy')->middleware('permission:units.delete');
    });

    /**
     * MATERIALS
     */
    Route::middleware(['permission:materials.view'])->group(function () {
        Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
        Route::get('/materials/create', [MaterialController::class, 'create'])->name('materials.create')->middleware('permission:materials.create');
        Route::post('/materials', [MaterialController::class, 'store'])->name('materials.store')->middleware('permission:materials.create');

        Route::get('/materials/{material}', [MaterialController::class, 'show'])->name('materials.show');

        Route::get('/materials/{material}/edit', [MaterialController::class, 'edit'])->name('materials.edit')->middleware('permission:materials.update');
        Route::put('/materials/{material}', [MaterialController::class, 'update'])->name('materials.update')->middleware('permission:materials.update');

        Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy')->middleware('permission:materials.delete');
    });

    /**
     * PRODUCTS
     */
    Route::middleware(['permission:products.view'])->group(function () {

        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create')->middleware('permission:products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store')->middleware('permission:products.create');

        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit')->middleware('permission:products.update');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update')->middleware('permission:products.update');

        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('permission:products.delete');
    });

    /**
     * ORDERS
     */
    Route::middleware(['permission:orders.view'])->group(function () {

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

        Route::get('/orders/create', [OrderController::class, 'create'])
            ->name('orders.create')
            ->middleware('permission:orders.create');

        Route::post('/orders', [OrderController::class, 'store'])
            ->name('orders.store')
            ->middleware('permission:orders.create');

        Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])
            ->name('orders.status.update')
            ->middleware('permission:orders.update_status');

        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])
            ->name('orders.cancel')
            ->middleware('permission:orders.update_status');

        // ✅ FIXED HERE
        Route::post('/orders/{order}/undo-cancel', [OrderController::class, 'undoCancel'])
            ->name('orders.undoCancel')
            ->middleware('permission:orders.update_status');
    });

    /**
     * REPORTS
     */
    Route::middleware(['permission:reports.view'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    /**
     * SUPPLIERS
     */
    Route::middleware(['permission:suppliers.view'])->group(function () {
        Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    });

    /**
     * PURCHASES
     */
    Route::middleware(['permission:purchases.view'])->group(function () {
        Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    });

    /**
     * EMPLOYEES
     */
    Route::middleware(['permission:employees.view'])->group(function () {
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    });

    /**
     * ATTENDANCE
     */
    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->name('attendance.index')
        ->middleware('permission:attendance.view');

    /**
     * PREDICTIONS
     */
    Route::get('/predictions', [PredictionController::class, 'index'])
        ->name('predictions.index')
        ->middleware('permission:orders.view');

});
