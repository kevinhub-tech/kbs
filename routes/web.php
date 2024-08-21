<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;



Route::controller(VendorController::class)->prefix('vendor')->group(function () {
    Route::middleware('vendor-only')->group(function () {
        Route::get('/book/{id}', 'bookdescription')->name('vendor.bookdesc');
        Route::get('/book-listing', 'booklisting')->name('vendor.book-listing');
        Route::get('/post-book', 'book')->name('vendor.book');
        Route::get('/edit-book/{id?}', 'editbook')->name('vendor.book-edit');
        Route::get('/logout', 'logout')->name("vendor.logout");
    });
    Route::get('/login', 'login')->name('vendor.login');
    Route::post('/signin', 'signin')->name('vendor.manuallogin');
});

Route::controller(AdminController::class)->prefix('admin')->group(function () {
    Route::middleware('admin-only')->group(function () {
        Route::get('/', 'home')->name('admin.home');
        Route::get('/logout', 'logout')->name("admin.logout");
    });
    Route::get('/login', 'login')->name('admin.login');
    Route::post('/signin', 'signin')->name('admin.manuallogin');
});

Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::get('/login', 'login')->name('user.login');
    Route::get('/register', 'register')->name('user.register');
    Route::middleware('user-only')->group(function () {
        Route::get('/', 'home')->name("user.home");
        Route::get('/cart', 'cart')->name("user.cart");
        Route::get('/favourite', 'favourite')->name("user.favourite");
        Route::get('/logout', 'logout')->name("user.logout");
        Route::get('/book/{id}', 'book')->name('user.book');
    });
    Route::post('/signin', 'signin')->name('user.manuallogin');
    Route::post('/signup', 'signup')->name('user.manualregister');

    Route::get('/auth/{social}/redirect', 'auth')->name('auth.login');
    Route::get('/auth/{social}/callback', 'authcallback')->name('auth.callback');
});

Route::get('/accessDenied', function () {
    return view('accessDenied');
})->name('accessDeny');

Route::get('/', function () {
    return view('main');
})->name('home');
