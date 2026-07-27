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

Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/profile', 'admin.profile')->name('profile');
    Route::view('/sections', 'admin.coming-soon', ['title' => 'Sections'])->name('sections');
    Route::view('/skills', 'admin.skills')->name('skills');
    Route::view('/projects', 'admin.projects')->name('projects');
    Route::view('/experience', 'admin.experience')->name('experience');
    Route::view('/education', 'admin.education')->name('education');
    Route::view('/theme', 'admin.coming-soon', ['title' => 'Theme'])->name('theme');
    Route::view('/settings', 'admin.settings')->name('settings');
});
