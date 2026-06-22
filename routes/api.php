<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\StockController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\ReturnController;
use Illuminate\Support\Facades\auth;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('warehouse')->group(function(){ 

    Route::get('/products',[ProductController::class, 'index']);
    Route::post('/products',[ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // Route::post('/scan', [ScanController::class, 'scan']); //search all tdk d pke
    Route::get('scan/{sku}', [ScanController::class, 'scan']);
    
    Route::post('/stock-in', [StockController::class, 'stockIn']);
    Route::post('/stock-out', [StockController::class, 'stockOut']);
    Route::get('/transactions', [StockController::class, 'history']);
    
    Route::get('/dashboard', [DashboardController::class, 'index']);

    //stock opname
    Route::get('/stock-opname/history',[StockOpnameController::class,'history']); //belum pakai

    Route::get('/stock-opname/active-session',[StockOpnameController::class, 'activeSession']);
    //Route::post('/returns', [ReturnController::class, 'store']);

});