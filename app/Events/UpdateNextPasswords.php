<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UpdateNextPasswords implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $passwords;

    public function __construct($passwords)
    {
        $this->passwords = $passwords;
    }

    public function broadcastOn(){
        return new Channel('passwords');
    }

    public function broadcastWith(){
        return [
            'passwords' => $this->passwords
        ];
    }
}
