<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConversationDiscussionResource;
use App\Http\Resources\GroupDiscussionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscussionController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        $conversations = $user->conversations()
            ->with(['user1:id,name,email,last_seen_at', 'user1.image', 'user2:id,name,email,last_seen_at', 'user2.image', 'lastMessage'])
            ->orderBy('last_message_id', 'desc')
            ->get();

        $joinedGroups = $user->groups;
        $ownedGroups = $user->ownedGroups;
        $groups = $joinedGroups->merge($ownedGroups);

        $conversations = $conversations->map(function ($conversation) {
            return (new ConversationDiscussionResource($conversation))->resolve();
        });

        $groups = $groups->map(function ($group) {
            return (new GroupDiscussionResource($group))->resolve();
        });

        $discussions = collect($conversations)
            ->merge(collect($groups))
            ->sortByDesc(function ($discussion) {
                return $discussion['last_message']['created_at'] ?? null;
            })
            ->values();

        return response()->json([
            'discussions' => $discussions,
        ]);
    }
}
