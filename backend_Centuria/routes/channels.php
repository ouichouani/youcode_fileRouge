<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('conversations.{conversation}', function ($user, $conversation) {
    $conversation = Conversation::find($conversation);
    $user1 = $conversation->user1;
    $user2 = $conversation->user2;
    if ($user->id === $user1->id || $user->id === $user2->id) {
        return ['id' => $user->id, 'name' => $user->name];
    }
    return false;

});

// CHANNEL THAT GET ALL MESSAGES IN THE GROUP
Broadcast::channel('groups.{group}', function ($user, $group) {
    return true;
});

// CHANNEL THAT GET ALL INTERACTIONS IN THE CHAL LIST LIKE
// NEW RECIEVED MESSAGE
// ONLINE AND OFLINE USERS

// Broadcast::channel('discussions.{user}', function ($user) {
//     $conversation1 = Conversation::where('user1_id', Auth::id())->pluck('user2_id')->toArray();
//     $conversation2 = Conversation::where('user2_id', Auth::id())->pluck('user1_id')->toArray();
//     $conversation = array_merge($conversation1, $conversation2);
//     if(in_array($user->id, $conversation)) return $user ;
//     return false;
// });



// CHANNEL THAT GET ALL ONLINE USERS IN THE SYSTEM
Broadcast::channel('online-users' , function($user){
    return [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
    ];
});

// NOTIFICATION CHANNELS TO SEND ANY EVENT RELATED TO THIS USER IN IT'S OWN CHANNEL
Broadcast::channel('users.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});