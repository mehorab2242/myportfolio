<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Blade UI)
|--------------------------------------------------------------------------
| Auth is handled client-side via Sanctum API tokens (localStorage).
| These routes only serve views. No Laravel session auth middleware.
*/

Route::get('/', function () {
    return view('welcome');
});

Route::view('/login', 'auth.login')->name('login');
Route::view('/dashboard', 'dashboard')->name('dashboard');
