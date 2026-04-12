<?php

namespace App\Policies;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FriendRequestPolicy
{

    public function accept(User $user, FriendRequest $friendRequest): bool
    {
            return $user->id === $friendRequest->receiver_id;
    }

    public function reject(User $user, FriendRequest $friendRequest): bool
    {
        return $user->id === $friendRequest->receiver_id;
    }

    public function delete(User $user, FriendRequest $friendRequest): bool
    {
        if($user->id === $friendRequest->sender_id || $user->id === $friendRequest->receiver_id){
            return true;
        }
        return false;
    }


}
