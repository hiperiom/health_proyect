<?php

namespace App\Observers;

use App\Models\Role;
use App\Events\Role\CreatedEvent;
use App\Events\Role\UpdatedEvent;
use App\Events\Role\DeletedEvent;

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     */
    public function created(Role $role): void
    {
        event(new CreatedEvent($role));
    }

    /**
     * Handle the Role "updated" event.
     */
    public function updated(Role $role): void
    {
        event(new UpdatedEvent($role));
    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        event(new DeletedEvent($role));
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
