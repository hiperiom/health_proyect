<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use App\Models\MedicEspeciality;
use App\Models\Role;
use App\Models\User;
use App\Observers\MedicEspecialityObserver;
use App\Observers\RoleObserver;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        MedicEspeciality::observe(MedicEspecialityObserver::class);
        Role::observe(RoleObserver::class);
        User::observe(UserObserver::class);
    }
}
