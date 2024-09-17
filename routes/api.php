<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;



Route::controller(VendorController::class)->prefix('vendor')->group(function () {
    Route::post('regenerate-link', ('regeneratelink'))->name('vendor.regenerate-link');
    Route::middleware('vendor-only')->group(function () {
        Route::post('/post-book', 'postbook')->name('vendor.book-post');
        Route::post('/create-discount', 'creatediscount')->name('vendor.discount-create');
        Route::post('/apply-discount', 'applydiscount')->name('vendor.discount-apply');
        Route::post('/edit-discount', 'editdiscount')->name('vendor.discount-edit');
        Route::post('/remove-discount', 'removediscount')->name('vendor.discount-remove');
        Route::delete('/delete-discount', 'deletediscount')->name('vendor.discount-delete');
        Route::post('/update-order-status', 'updateorderstatus')->name('vendor.updateorderstatus');
        Route::put('/update-book', 'updatebook')->name('vendor.book-update');
        Route::post('/update-vendor-info', 'updatevendorinfo')->name('vendor.updatevendorinfo');
    });
});

Route::controller(AdminController::class)->prefix('admin')->group(function () {
    Route::middleware('admin-only')->group(function () {
        Route::post('/update-application-status', 'updateapplicationstatus')->name('admin.updateapplicationstatus');
    });
});

Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::middleware('user-only')->group(function () {
        Route::post('/add-cart', 'addcart')->name('user.addcart');
        Route::post('/add-favourite', 'addfavourite')->name('user.addfavourite');
        Route::post('/update-cart', 'updatecart')->name('user.updatecart');
        Route::post('/remove-cart', 'removecart')->name('user.removecart');
        Route::post('/remove-favourite', 'removefavourite')->name('user.removefavourite');
        Route::post('/send-order', 'sendorder')->name('user.sendorder');
        Route::post('/update-order-status', 'updateorderstatus')->name('user.updateorderstatus');
        Route::post('/update-profile', 'updateprofile')->name('user.updateprofile');
        Route::post('/update-default-address', 'setdefaultaddress')->name('user.updatedefaultaddress');
        Route::post('/add-review', 'addreview')->name('user.addreview');
        Route::post('/update-review', 'updatereview')->name('user.updatereview');
    });
    Route::post('/reset-forget-password-token', 'resetforgetpasswordtoken')->name('user.resetforgetpasswordtoken');
});



Route::get('book-image/{route}/{image}', function (String $route, String $image) {
    $path = storage_path('app/' . $route . '/' . $image);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->name('get-image');
