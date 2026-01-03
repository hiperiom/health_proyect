<?php

namespace App\Observers;

use App\Models\User;
use App\Events\User\CreatedEvent;
use App\Events\User\UpdatedEvent;
use App\Events\User\DeletedEvent;
use App\Services\UserProfile\StoreService;
class UserObserver
{
    public function __construct(
        protected StoreService $profileService
    ) {}
    /**
     * Handle the Role "created" event.
     */
    public function created(User $user): void
    {
        $user->assignRole('Paciente');
        
        $this->profileService->execute(
            $user->id, 
            request()->all()
        );
        event(new CreatedEvent($user));
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        event(new UpdatedEvent($user));
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        event(new DeletedEvent($user));
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}