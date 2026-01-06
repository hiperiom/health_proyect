<?php

namespace App\Events\MedicEspeciality;

use App\Models\MedicEspeciality;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeletedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $medicEspecialities;

    public function __construct($medicEspecialities)
    {
        $this->medicEspecialities = $medicEspecialities;
    }
    public function broadcastOn(): array
    {
        return [new Channel('medic-especialities')];
    }

    public function broadcastAs(): string
    {
        return 'medic-especialities.deleted';
    }
}