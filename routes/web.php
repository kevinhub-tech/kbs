<?php

use Illuminate\Support\Facades\Route;

Route::prefix('vendor')->group(function () {
    Route::get('/login', function () {
        return view('login');
    })->name('vendor.login');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('login');
    })->name('admin.login');
});

Route::prefix('user')->group(function () {
    Route::get('/login', function () {
        return view('login');
    })->name('user.login');
    Route::get('/home', function () {
        return view('users.home');
    });
});

Route::get('/', function () {
    return view('main');
});
