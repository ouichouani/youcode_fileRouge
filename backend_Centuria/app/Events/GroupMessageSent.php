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

class GroupMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Message $message)
    {
        //
    }

    public function broadcastOn(): array
    {

        // $members_event = $this->message->group->members->map(function ($member) {
        //     return new PrivateChannel('users.' . $member->id);
        // })->toArray();

        return [
            new PresenceChannel("groups." . $this->message->group->id),
            // new PrivateChannel('users.' . $this->message->group->owner_id),
            // NOTIFY EVERY USER IN THE GROUP
            // ...$members_event, 

        ];
    }

    public function broadcastAs()
    {
        return 'group.message.sent';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message->load('sender.image'),
            'group_id' => $this->message->group->id,
        ];
    }
}
