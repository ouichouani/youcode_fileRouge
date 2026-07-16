<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupDiscussionResource extends JsonResource
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
            'type' => 'group',
            'id' => $this->id,
            'title' => $this->name,
            'avatar' => $this->image,
            'last_message' => $this->lastMessage,
            'members_count' => $this->members()->count(),
        ];
    }
}
