<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SkuController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\VialController;

// Test route to confirm API file is working
Route::get('/test', function () {
    return response()->json(['message' => 'API routes working fine']);
});

// Your actual routes
Route::get('/getAllSku', [SkuController::class, 'getAllSku']);
Route::any('/getAllBatch', [SkuController::class, 'getAllBatch']);
Route::any('/getBatchDetails', [SkuController::class, 'getBatchDetails']);
Route::any('/getData', [VialController::class, 'getData']);
Route::any('/getAllVials', [VialController::class, 'getAllVials']);