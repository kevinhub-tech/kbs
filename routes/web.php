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
    Route::post('/signin', 'signin')->name('vendor.manuallogin');
});

Route::controller(AdminController::class)->prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('login');
    })->name('admin.login');
    Route::post('/signin', 'signin')->name('admin.manuallogin');
});

Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::get('/login', 'login')->name('user.login');
    Route::get('/register', 'register')->name('user.register');
    Route::post('/signin', 'signin')->name('user.manuallogin');
    Route::post('/signup', 'signup')->name('user.manualregister');
    Route::get('/', 'home')->name("user.home");
    Route::get('/auth/{social}/redirect', 'auth')->name('auth.login');
    Route::get('/auth/{social}/callback', 'authcallback')->name('auth.callback');
});


Route::get('/', function () {
    return view('main');
});
