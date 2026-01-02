<?php

namespace App\Observers;

use App\Models\Role;
use App\Events\RoleCreated;
use App\Events\RoleUpdated;
use App\Events\RoleDeleted;

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     */
    public function created(Role $role): void
    {
        event(new RoleCreated($role));
    }

    /**
     * Handle the Role "updated" event.
     */
    public function updated(Role $role): void
    {
        event(new RoleUpdated($role));
    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        event(new RoleDeleted($role));
    }

    /**
     * Handle the Role "restored" event.
     */
    public function restored(Role $role): void
    {
        //
    }

    /**
     * Handle the Role "force deleted" event.
     */
    public function forceDeleted(Role $role): void
    {
        //
    }
}
