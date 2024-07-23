<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main');
});

Route::get('/user/home', function () {
    return view('users.home');
});

Route::get('/user/login', function () {
    return view('login');
});
