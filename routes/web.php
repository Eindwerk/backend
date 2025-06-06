<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminRegisterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// GET shows the password form, with signed validation
Route::get('/register/{user}', [AdminRegisterController::class, 'showForm'])
    ->middleware('signed')
    ->name('admin.register');

// POST saves the password
Route::post('/register/{user}', [AdminRegisterController::class, 'storePassword'])
    ->name('admin.register.submit');

// POST to handle logout
Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');
