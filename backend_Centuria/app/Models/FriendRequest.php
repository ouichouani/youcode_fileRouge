<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FriendRequest extends Model
{
    /** @use HasFactory<\Database\Factories\FriendRequestFactory> */
    use HasFactory;

    protected $fillable = ['status', 'sender_id', 'receiver_id'];


    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // USED IN CHAT , WHEN A USER WANT TO START A CONVERSATION 
    // THIS FUNCTION GET ALL NON BANED USERS THAT ARE FRIENDS WITH THE GIVING USER
    public static function allFriends(User $user)
    {    
        return User::whereExists(function ($q) use ($user) {
            $q->selectRaw('1')
                ->from('friend_requests')
                ->where('status', 'accepted')
                ->where('is_banned', false)
                ->where('is_banned_by_moderator', false)
                ->where(function ($q) use ($user) {
                    
                    $q->where(function ($q) use ($user) {
                        $q->whereColumn('friend_requests.sender_id', 'users.id')
                        ->where('friend_requests.receiver_id', $user->id);
                    })

                    ->orWhere(function ($q) use ($user) {
                        $q->whereColumn('friend_requests.receiver_id', 'users.id')
                        ->where('friend_requests.sender_id', $user->id);
                    });
                });
        })
        ->orderBy('name', 'asc')
            ->with('image')
            ->get();



        }

}
