<?php

namespace App\Events\UserProfile;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeletedEvent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $userprofile;

	public function __construct($userprofile)
	{
		$this->userprofile = $userprofile;
	}

	public function broadcastOn(): array
	{
		return [
			new Channel('user-profiles'),
		];
	}

	public function broadcastAs(): string
	{
		return 'user-profiles.deleted';
	}
}