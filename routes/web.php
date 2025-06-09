<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminRegisterController;

// GET shows the password form, with signed validation
Route::get('/register/{user}', [AdminRegisterController::class, 'showForm'])
    ->middleware('signed')
    ->name('admin.register');

// POST saves the password
Route::post('/register/{user}', [AdminRegisterController::class, 'storePassword'])
    ->name('admin.register.submit');
