<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;



Route::controller(VendorController::class)->prefix('vendor')->group(function () {
    Route::middleware('vendor-only')->group(function () {
        Route::post('/post-book', 'postbook')->name('vendor.book-post');
        Route::post('/create-discount', 'creatediscount')->name('vendor.discount-create');
        Route::post('/apply-discount', 'applydiscount')->name('vendor.discount-apply');
        Route::post('/edit-discount', 'editdiscount')->name('vendor.discount-edit');
        Route::post('/remove-discount', 'removediscount')->name('vendor.discount-remove');
        Route::delete('/delete-discount', 'deletediscount')->name('vendor.discount-delete');
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
        Route::post('/update-cart', 'updatecart')->name('user.updatecart');
        Route::post('/remove-cart', 'removecart')->name('user.removecart');
        Route::post('/remove-favourite', 'removefavourite')->name('user.removefavourite');
    });
});

Route::get('book-image/{image}', function (String $image) {
    $path = storage_path('app/books/' . $image);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->name('get-image');
