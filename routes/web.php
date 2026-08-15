<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\ReportController;
use App\Models\RackSlot;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Landing Page -->> login Page
|--------------------------------------------------------------------------
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:owner,leader'])->group(function () {

    Route::get('/dashboard', function () {
        return view('warehouse-dashboard');
    })->name('dashboard');

    Route::get('/dashboard/data', [DashboardController::class, 'index']);

});

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

/*
|--------------------------------------------------------------------------
| Inventory
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:owner,leader,staff'])->group(function () {

    Route::get('/inventory', function () {
        $rackSlots = RackSlot::with('rack')->get();
        return view('inventory', compact('rackSlots'));
    });

    Route::get('/inventory/products', [ProductController::class, 'index']);
    Route::post('/inventory/products', [ProductController::class, 'store']);
    Route::get('/inventory/products/{id}', [ProductController::class, 'show']);
    Route::put('/inventory/products/{id}', [ProductController::class, 'update']);
    Route::delete('/inventory/products/{id}', [ProductController::class, 'destroy']);

});


/*
|--------------------------------------------------------------------------
| Transactions
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:leader,staff'])->group(function () {

    Route::get('/transactions-in', function () {
        return view('transactions-in');
    });

    Route::get('/transactions-out', function () {
        return view('transactions-out');
    });

    Route::post('/warehouse/stock-in', [StockController::class, 'stockIn']);
    Route::post('/warehouse/stock-out', [StockController::class, 'stockOut']);
    Route::put('warehouse/transactions/{id}', [StockController::class, 'update']);
    Route::get('/warehouse/scan/{sku}', [ScanController::class, 'scan']);
    Route::get('warehouse/transactions/history', [StockController::class, 'history']);

});

/*
|--------------------------------------------------------------------------
| Returns
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:leader,staff'])->group(function () {

    Route::get('/returns', [ReturnController::class, 'index']);
    Route::post('/returns', [ReturnController::class, 'store']);
    Route::get('/returns/data', [ReturnController::class, 'apiIndex']);
    Route::put('/returns/{id}', [ReturnController::class, 'update']);
    Route::post('/returns/{id}/cancel', [ReturnController::class, 'cancel']);
    Route::get('/returns/review', [ReturnController::class, 'review']);
    Route::patch('/returns/{id}/approve', [ReturnController::class, 'approve']);
    Route::patch('/returns/{id}/reject', [ReturnController::class, 'reject']);

});

/*
|--------------------------------------------------------------------------
| Stock Opname
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:leader'])->group(function () {


    Route::get('/stock-opname', [StockOpnameController::class, 'index']);
    Route::get('/warehouse/products', [ProductController::class, 'index']);
    Route::get('/warehouse/products/{id}', [ProductController::class, 'show']);
    Route::post('/warehouse/stock-opname', [StockOpnameController::class, 'store']);

    Route::post('/warehouse/stock-opname/start', [StockOpnameController::class, 'startSession']);
    Route::post('/warehouse/stock-opname/close', [StockOpnameController::class, 'closeSession']);
    Route::get('/warehouse/stock-opname/history', [StockOpnameController::class, 'history']);
    Route::get('/warehouse/stock-opname/active', [StockOpnameController::class, 'activeSession']);

});

/*
|--------------------------------------------------------------------------
| Reports
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:owner'])->group(function () {

    Route::get('/reports/index', [ReportController::class, 'index']);

});

/*
|--------------------------------------------------------------------------
| QR Code
|--------------------------------------------------------------------------
*/

Route::get('/qr/{sku}', [ScanController::class, 'generate']);

require __DIR__.'/auth.php';