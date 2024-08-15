<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;



Route::controller(VendorController::class)->prefix('vendor')->group(function () {
    Route::middleware('vendor-only')->group(function () {
        Route::post('/post-book', 'postbook')->name('vendor.book-post');
        Route::put('/update-book', 'updatebook')->name('vendor.book-update');
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
        Route::post('/add-cart', 'addcart')->name('user.addcart');
        Route::post('/add-favourite', 'addfavourite')->name('user.addfavourite');
    });
});

Route::get('book-image/{image}', function (String $image) {
    $path = storage_path('app/books/' . $image);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->name('get-image');
