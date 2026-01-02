<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RolesController;
use Illuminate\Support\Facades\Route;


Route::get('/',[AuthController::class,'index']);

Route::middleware([
    'auth:sanctum', 
    config('jetstream.auth_session'), 
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    
    Route::get('/roles/data', [RolesController::class, 'data'])->name('roles.data');
    Route::resource('roles', RolesController::class);

});
