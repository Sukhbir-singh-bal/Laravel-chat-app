<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrivateChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;
    public $userID;
    public $reciverID;
    /**
     * Create a new event instance.
     */
    public function __construct($userID,$reciverID,$message)
    {
        $this->message = $message;
        $this->userID = $userID;
        $this->reciverID = $reciverID;
    }

    

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->userID . '.' . $this->reciverID),
        ];
    }
}
