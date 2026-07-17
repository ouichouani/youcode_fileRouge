<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message)
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('conversations.'.$this->message->conversation->id),
            new PrivateChannel('users.'.$this->message->conversation->user1_id),
            new PrivateChannel('users.'.$this->message->conversation->user2_id),
        ];
    }

    public function broadcastAs()
    {
        return 'conversation.message.sent';
    }

    public function broadcastWith()
    {
        return [
            'conversation_id' => $this->message->conversation->id,
            'message' => $this->message, // you send the conversation object with the message, so you can access the conversation details in the frontend
        ];
    }
}
