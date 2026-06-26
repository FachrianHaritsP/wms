<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\StockController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ReturnController;
use Illuminate\Support\Facades\auth;

//authflutter
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')
->group(function(){

    Route::get(
        '/user',
        [AuthController::class, 'user']
    );

    Route::post(
        '/logout',
        [AuthController::class, 'logout']
    );

});

//web + flutter
Route::prefix('warehouse')
    ->middleware('auth:sanctum')
    ->group(function(){

    //product
    Route::get('/products',[ProductController::class, 'index']);
    Route::post('/products',[ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    //scan    
    Route::get('scan/{sku}', [ScanController::class, 'scan']);
    //stock    
    Route::post('/stock-in', [StockController::class, 'stockIn']);
    Route::post('/stock-out', [StockController::class, 'stockOut']);
    Route::get('/transactions', [StockController::class, 'history']);
    //dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    //stock opname
    Route::get('/stock-opname/history',[StockOpnameController::class,'history']); //belum pakai
    Route::get('/stock-opname/active-session',[StockOpnameController::class, 'activeSession']);

});