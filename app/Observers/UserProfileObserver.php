<?php

namespace App\Observers;

use App\Models\UserProfile;
use App\Events\UserProfile\CreatedEvent;
use App\Events\UserProfile\UpdatedEvent;
use App\Events\UserProfile\DeletedEvent;

class UserProfileObserver
{
    public function created(UserProfile $userprofile): void
    {
        event(new CreatedEvent($userprofile));
    }

    public function updated(UserProfile $userprofile): void
    {
        event(new UpdatedEvent($userprofile));
    }

    public function deleted(UserProfile $userprofile): void
    {
        event(new DeletedEvent($userprofile));
    }

    public function restored(UserProfile $userprofile): void
    {
        //
    }

    public function forceDeleted(UserProfile $userprofile): void
    {
        //
    }
}