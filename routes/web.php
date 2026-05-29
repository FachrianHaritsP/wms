<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\StockOpnameController;
use App\Models\RackSlot;

Route::get('/', function () {
    return view('welcome');
});

//dashboard
Route::get('/dashboard', function () {
    return view('warehouse-dashboard');
})->middleware(['auth', 'role:owner,leader'])->name('dashboard'); #verified

//belum kepake manage user
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//inventory
Route::middleware(['auth', 'role:leader'])->group(function () {

    Route::get('/inventory', function () {
        return view('inventory');
    });

}); 

Route::get('/inventory', function () {

    $rackSlots = RackSlot::with('rack')->get();

    return view('inventory', compact('rackSlots'));

})->middleware(['auth']);

/* Route::middleware(['auth', 'role:leader,staff'])->group(function () {

    Route::get('/transactions', function () {
        return view('transactions');
    });

});  */

//transactions
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


//returns
Route::get('/returns', function () {
    return view('returns');
})->middleware(['auth', 'role:leader,staff']);

Route::post('/returns', [ReturnController::class, 'store'])->middleware(['auth']);
Route::get('/returns',[ReturnController::class,'index'])->middleware(['auth']);
Route::get('/returns/review', [ReturnController::class,'review']);
Route::patch('/returns/{id}/approve',[ReturnController::class,'approve']);
Route::patch('/returns/{id}/reject',[ReturnController::class,'reject']);

//stockopname
Route::middleware(['auth', 'role:leader'])->group(function () {

   /*  Route::get('/stock-opname', function () {
        return view('stock-opname');
    }); */
    Route::get('/stock-opname',[StockOpnameController::class,'index']);
    Route::post('/stock-opname',[StockOpnameController::class,'store']);
    Route::get('/stock-opname/data',[StockOpnameController::class,'data']);
    Route::get('/warehouse/scan/{barcode}',[ScanController::class,'scan']);
}); 



//another
Route::get('/qr/{sku}', [ScanController::class, 'generate']);

require __DIR__.'/auth.php';
