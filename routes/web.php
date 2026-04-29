<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/dashboard', function(){
    return view('index');
})->name('dashboard')->middleware('auth');

