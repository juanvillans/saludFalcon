<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

  public $messages;
  public $caseID;

  public function __construct($messages, $caseID)
  {
    $this->messages = $messages;
    $this->caseID = $caseID;

  }

  public function broadcastOn()
  {
      return ['generalChat', 'chat-'. $this->caseID];
  }

  public function broadcastAs()
  {
      return 'newMessage';
  }
}
