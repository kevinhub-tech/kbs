<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;



Route::controller(VendorController::class)->prefix('vendor')->group(function () {
    Route::middleware('vendor-only')->group(function () {
        Route::get('/get', 'demoget');
        Route::post('/post', 'demopost');
    });
});

Route::controller(AdminController::class)->prefix('admin')->group(function () {
    Route::middleware('admin-only')->group(function () {
        Route::get('/get', 'demoget');
        Route::post('/post', 'demopost');
    });
});

Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::middleware('user-only')->group(function () {
        Route::get('/get', 'demoget');
        Route::post('/post', 'demopost');
    });
});
