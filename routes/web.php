<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;


Route::controller(VendorController::class)->prefix('vendor')->group(function () {
    Route::get('/', function () {
        return view('vendors.home');
    });
    Route::get('/login', function () {
        return view('login');
    })->name('vendor.login');
});

Route::controller(AdminController::class)->prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('login');
    })->name('admin.login');
});

Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::get('/login', function () {
        return view('login');
    })->name('user.login');
    Route::get('/register', function () {
        return view('users.register');
    })->name('user.register');
    Route::get('/', function () {
        return view('users.home');
    });
    Route::get('/auth/{social}/redirect', 'auth')->name('auth.login');
    Route::get('/auth/{social}/callback', 'authcallback')->name('auth.callback');
});


Route::get('/', function () {
    return view('main');
});
