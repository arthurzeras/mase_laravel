<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ShowPasswordCalled implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $called;
    public $already_called;

    public function __construct($called, $already_called)
    {
        $this->called = $called;
        $this->already_called = $already_called;
    }

    public function broadcastOn()
    {
        return new Channel('on_call');
    }

    public function broadcastWith(){
        return [
            'called' => $this->called,
            'already_called' => $this->already_called
        ];
    }
}
