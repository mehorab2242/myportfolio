<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Blade UI)
|--------------------------------------------------------------------------
| Auth is handled client-side via Sanctum API tokens (localStorage).
| These routes only serve views. No Laravel session auth middleware.
*/

$reserved = implode('|', array_map(
    'preg_quote',
    config('portfolio.reserved_usernames', ['admin', 'login', 'register'])
));

Route::redirect('/', '/login');

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

// Legacy dashboard URL
Route::redirect('/dashboard', '/admin');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'dashboard')->name('dashboard');
    Route::view('/profile', 'admin.profile')->name('profile');
    Route::view('/sections', 'admin.coming-soon', ['title' => 'Sections'])->name('sections');
    Route::view('/skills', 'admin.skills')->name('skills');
    Route::view('/projects', 'admin.projects')->name('projects');
    Route::view('/experience', 'admin.experience')->name('experience');
    Route::view('/education', 'admin.education')->name('education');
    Route::view('/theme', 'admin.coming-soon', ['title' => 'Theme'])->name('theme');
    Route::view('/settings', 'admin.settings')->name('settings');
});

// Public portfolio: myportfolio.com/{username}
Route::view('/{username}', 'portfolio.show')
    ->where('username', '^(?!'.$reserved.')[a-z0-9](?:[a-z0-9_-]*[a-z0-9])?$')
    ->name('portfolio.show');
