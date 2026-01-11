<?php

namespace App\Observers;

use App\Models\Medic;
use App\Events\Medic\CreatedEvent;
use App\Events\Medic\UpdatedEvent;
use App\Events\Medic\DeletedEvent;

class MedicObserver
{
    public function created(Medic $medic): void
    {
        event(new CreatedEvent($medic));
    }

    public function updated(Medic $medic): void
    {
        event(new UpdatedEvent($medic));
    }

    public function deleted(Medic $medic): void
    {
        event(new DeletedEvent($medic));
    }

    public function restored(Medic $medic): void
    {
        //
    }

    public function forceDeleted(Medic $medic): void
    {
        //
    }
}