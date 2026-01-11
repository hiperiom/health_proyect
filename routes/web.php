<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\MedicEspecialityController;

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
    Route::get('/show/{id}', [AuthController::class, 'show'])->name('auth.profile');
    
    Route::get('/roles/data', [RolesController::class, 'data'])->name('roles.data');
    Route::resource('roles', RolesController::class);

    Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
    Route::resource( 'users', UserController::class);
    Route::post('users/{user}', [UserController::class, 'update'])->name('users.update');


    Route::get('/medic-especialities/data', [MedicEspecialityController::class, 'data'])->name('medic-especialities.data');
    Route::resource('medic-especialities', MedicEspecialityController::class);

    Route::get('/user-profile/data', [UserProfileController::class, 'data'])->name('user-profile.data');
    Route::resource('user-profile', UserProfileController::class);


});

