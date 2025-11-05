<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SkuController;
use App\Http\Controllers\Admin\BatchController;
Route::get('/', function () {
    return view('welcome');
});

Route::name('admin.')->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });
    Route::prefix('skus')->name('skus.')->group(function () {
        Route::get('/', [SkuController::class, 'index'])->name('index');
        Route::any('/add', [SkuController::class, 'add'])->name('add');
        Route::any('/update/{id}', [SkuController::class, 'update'])->name('update');
        Route::any('/delete/{id}', [SkuController::class, 'delete'])->name('delete');
        Route::any('/view/{id}', [SkuController::class, 'view'])->name('view');
    });
    Route::prefix('batches')->name('batches.')->group(function () {
        Route::get('/', [BatchController::class, 'index'])->name('index');
        Route::any('/add', [BatchController::class, 'add'])->name('add');
        Route::any('/update/{id}', [BatchController::class, 'update'])->name('update');
        Route::any('/view/{id}', [BatchController::class, 'view'])->name('view');
    });
});