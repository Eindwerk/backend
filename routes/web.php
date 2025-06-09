<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminRegisterController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

// GET shows the password form, with signed validation
Route::get('/register/{user}', [AdminRegisterController::class, 'showForm'])
    ->middleware('signed')
    ->name('admin.register');

// POST saves the password
Route::post('/register/{user}', [AdminRegisterController::class, 'storePassword'])
    ->name('admin.register.submit');

Route::get('/storage/{path}', function ($path) {
    $file = storage_path('app/public/' . $path);

    if (!File::exists($file)) {
        abort(404);
    }

    return Response::file($file);
})->where('path', '.*');
