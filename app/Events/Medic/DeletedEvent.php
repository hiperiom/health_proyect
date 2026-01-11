<?php

namespace App\Events\Medic;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeletedEvent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $medic;

	public function __construct($medic)
	{
		$this->medic = $medic;
	}

	public function broadcastOn(): array
	{
		return [
			new Channel('medics'),
		];
	}

	public function broadcastAs(): string
	{
		return 'medics.deleted';
	}
}