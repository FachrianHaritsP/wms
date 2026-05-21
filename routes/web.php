<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ReturnController;
use App\Models\RackSlot;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('warehouse-dashboard');
})->middleware(['auth', 'role:owner,leader'])->name('dashboard'); #verified

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:leader'])->group(function () {

    Route::get('/inventory', function () {
        return view('inventory');
    });

}); 

/* Route::middleware(['auth', 'role:leader,staff'])->group(function () {

    Route::get('/transactions', function () {
        return view('transactions');
    });

});  */

Route::middleware(['auth', 'role:leader,staff'])->group(function () {

    Route::get('/transactions-in', function () {
        return view('transactions-in');
    });

}); 

Route::middleware(['auth', 'role:leader,staff'])->group(function () {

    Route::get('/transactions-out', function () {
        return view('transactions-out');
    });

}); 

Route::middleware(['auth'])->group(function () {

    Route::post('/warehouse/stock-in', [StockController::class, 'stockIn']);

    Route::post('/warehouse/stock-out', [StockController::class, 'stockOut']);

});

//inventory
Route::get('/inventory', function () {

    $rackSlots = RackSlot::with('rack')->get();

    return view('inventory', compact('rackSlots'));

})->middleware(['auth']);

//playground
Route::get('/returns-test', function () {
    return view('returns-test');
})->middleware(['auth']);

Route::post('/returns', [ReturnController::class, 'store'])->middleware(['auth']);

//another
Route::get('/qr/{sku}', [ScanController::class, 'generate']);

require __DIR__.'/auth.php';
