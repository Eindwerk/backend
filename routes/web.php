<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminRegisterController;

Route::get('/', function () {
    return view('welcome');
});

// GET shows the password form, with signed validation
Route::get('/admin/register/{user}', [AdminRegisterController::class, 'showForm'])
    ->middleware('signed')
    ->name('admin.register');

// POST saves the password
Route::post('/admin/register/{user}', [AdminRegisterController::class, 'storePassword'])
    ->name('admin.register.submit');
