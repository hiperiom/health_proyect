<?php

namespace App\Observers;

use App\Models\MedicEspeciality;
use App\Events\MedicEspeciality\CreatedEvent;
use App\Events\MedicEspeciality\UpdatedEvent;
use App\Events\MedicEspeciality\DeletedEvent;
use Illuminate\Support\Facades\Log;

class MedicEspecialityObserver
{
    public function created(MedicEspeciality $medicespeciality): void
    {
        event(new CreatedEvent($medicespeciality));
    }

    public function updated(MedicEspeciality $medicespeciality): void
    {
        event(new UpdatedEvent($medicespeciality));
    }

    public function deleted(MedicEspeciality $medicespeciality): void
    {
        event(new DeletedEvent($medicespeciality));
    }

    public function restored(MedicEspeciality $medicespeciality): void
    {
        //
    }

    public function forceDeleted(MedicEspeciality $medicespeciality): void
    {
        //
    }
}
