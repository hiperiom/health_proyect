<?php

use App\Http\Controllers\ExampleController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/',[AuthController::class, 'index']);
Route::get('/example', [ExampleController::class,'index'] )->name('example');
Route::get('/example2', [ExampleController::class,'index2'] )->name('example2');  


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
