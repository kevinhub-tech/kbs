<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

Route::controller(AdminController::class)->prefix('admin')->group(function () {
    Route::middleware('admin-only')->group(function () {
        Route::get('/', 'dashboard')->name('admin.dashboard');
        Route::get('/vendor', 'vendors')->name('admin.vendors');
        Route::get('/logout', 'logout')->name("admin.logout");
    });
    Route::get('/login', 'login')->name('admin.login');
    Route::post('/signin', 'signin')->name('admin.manuallogin');
});

Route::controller(VendorController::class)->prefix('vendor')->group(function () {
    Route::middleware('vendor-only')->group(function () {
        Route::get('/dashboard', 'dashboard')->name('vendor.dashboard');
        Route::get('/book/{id}', 'bookdescription')->name('vendor.bookdesc');
        Route::get('/book-listing', 'booklisting')->name('vendor.book-listing');
        Route::get('/order-listing', 'orderlisting')->name('vendor.order-listing');
        Route::get('/discount', 'discountlisting')->name('vendor.discount-listing');
        Route::get('/post-book', 'book')->name('vendor.book');
        Route::get('/edit-book/{id?}', 'editbook')->name('vendor.book-edit');
        Route::get('/logout', 'logout')->name("vendor.logout");
    });
    Route::get('/login', 'login')->name('vendor.login');
    Route::post('/signin', 'signin')->name('vendor.manuallogin');
    Route::post('/sendapplication', 'sendapplication')->name('vendor.sendapplication');
    Route::post('/postvendorinfo/{token}', 'postvendorinfo')->name('vendor.postvendorinfo');
    Route::get('/vendor-application', 'vendorapplication')->name('vendor.vendorapplication');
    Route::get('/vendor-info/{token}', 'vendorinfo')->name('vendor.vendorinfo');
    Route::get('/vendor-profile/{id}', 'vendorprofile')->name('vendor.vendorprofile');
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
        Route::get('/checkout', 'checkout')->name('user.checkout');
        Route::get('/address', 'address')->name('user.address');
        Route::get('/orderlisting', 'order')->name('user.orderlisting');
        Route::get('/reviews', 'reviews')->name('user.reviews');
    });
    Route::get('/order', 'orderdetail')->name('user.orderdetail');
    Route::get('/ordertracking', function () {
        return view('order-tracking');
    })->name('user.ordertracking');
    Route::get('/forgetpassword', function () {
        return view('users.forget-password');
    })->name('user.forgetpassword');
    Route::post('/forgetpasswordresettoken', 'sendforgetpasswordlink')->name('user.sendforgetpassword');
    Route::post('/passwordreset', 'passwordreset')->name('user.passwordreset');
    Route::get('/passwordresetp/{id}', 'passwordresetpage')->name('user.passwordresetpage');
    Route::post('/resetpassword', 'resetpassword')->name('user.resetpassword');
    Route::get('/fpresettoken/{id}', 'fpresettoken')->name('user.fpresettoken');
    Route::post('/signin', 'signin')->name('user.manuallogin');
    Route::post('/signup', 'signup')->name('user.manualregister');

    Route::get('/auth/{social}/redirect', 'auth')->name('auth.login');
    Route::get('/auth/{social}/callback', 'authcallback')->name('auth.callback');
});

Route::get('/accessDenied', function () {
    return view('accessDenied');
})->name('accessDeny');

Route::get('/', function () {
    return view('home');
})->name('home');
Route::get('/tnc', function () {
    return view('termsandcondition');
})->name('tnc');
Route::get('/ufaq', function () {
    return view('userfaq');
})->name('ufaq');
Route::get('/vfaq', function () {
    return view('vendorfaq');
})->name('vfaq');
Route::get('/htb', function () {
    return view('howtobuy');
})->name('htb');
