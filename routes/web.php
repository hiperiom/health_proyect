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
    /* Route::group(['prefix' => 'users'], function () {
        Route::get('/index', function () {
            return Inertia::render('Dashboard/Administrator/Users/Index');
        })->name('admin.users');
        Route::get('/create', function () {
            return Inertia::render('Dashboard/Administrator/Users/Create');
        })->name('admin.users.create');
        Route::get('/edit/{id}', function () {
            return null;
        })->name('admin.users.edit');
    });
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/index', function () {
            return Inertia::render('Dashboard/Administrator/Roles/Index');
        })->name('admin.roles');
        Route::get('/create', function () {
            return Inertia::render('Dashboard/Administrator/Roles/Create');
        })->name('admin.roles.create');
        Route::get('/edit/{id}', function () {
            return null;
        })->name('admin.roles.edit');
    }); */
});
