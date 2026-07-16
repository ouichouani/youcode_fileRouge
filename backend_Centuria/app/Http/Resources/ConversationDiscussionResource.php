<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationDiscussionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'type' => 'conversation',
            'id' => $this->id,
            'title' => $this->user->name,
            'avatar' => $this->user->image,
            'last_message' => $this->lastMessage,
            'user' => $this->user,
        ];
    }
}
