<?php

namespace App\Observers;

use App\Models\Medic;
use App\Events\Medic\CreatedEvent;
use App\Events\Medic\UpdatedEvent;
use App\Events\Medic\DeletedEvent;
use App\Services\UserProfile\StoreService;
class MedicObserver
{
    public function __construct(
        protected StoreService $profileService
    ) {}
    public function created(Medic $medic): void
    {
        $medic->assignRole('Paciente');
        $medic->assignRole('Médico');
        
        $this->profileService->execute(
            $medic->id, 
            request()->all()
        );
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