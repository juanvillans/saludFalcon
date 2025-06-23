<?php

namespace App\Events;

use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

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
      Log::info('Llamando broadcast');

      return ['generalChat', 'chat-'. $this->caseID];
  }

  public function broadcastAs()
  {
     Log::info('Llamando broadcast 2');

      return 'newMessage';
  }
}
