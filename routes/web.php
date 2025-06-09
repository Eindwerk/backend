<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminRegisterController;
use Filament\Http\Controllers\Auth\LogoutController;

// GET shows the password form, with signed validation
Route::get('/register/{user}', [AdminRegisterController::class, 'showForm'])
    ->middleware('signed')
    ->name('admin.register');

// POST saves the password
Route::post('/register/{user}', [AdminRegisterController::class, 'storePassword'])
    ->name('admin.register.submit');

Route::post('/logout', [LogoutController::class, 'logout'])
    ->name('admin.logout')
    ->middleware(['auth:filament', 'throttle:60,1']);
