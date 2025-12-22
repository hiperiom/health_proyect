<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/',[AuthController::class, 'index']);

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
        
    });
    Route::group(['prefix' => 'users'], function () {
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
    Route::group(['prefix' => 'rols_permissions'], function () {
        Route::get('/index', function () {
            return Inertia::render('Dashboard/Administrator/RolsPermitions/Index');
        })->name('admin.rols_permissions');
        Route::get('/create', function () {
            return Inertia::render('Dashboard/Administrator/RolsPermitions/Create');
        })->name('admin.rols_permissions.create');
        Route::get('/edit/{id}', function () {
            return null;
        })->name('admin.rols_permissions.edit');
    });
});
