<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Conversation extends Model
{
    /** @use HasFactory<\Database\Factories\ConversationFactory> */
    use HasFactory;

    protected $fillable = [
        'user1_id',
        'user2_id',
        'last_message_id',
    ];

    protected $hidden = [
        'user1',
        'user2',
        'last_message_id',
    ];

    protected $appends = ["user"];




    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->belongsTo(Message::class, 'last_message_id');
    }

    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

 
    public function getUserAttribute()
    {
        $authId = Auth::id();

        $user = $this->user1_id == $authId
            ? $this->User = $this->user2
            : $this->User = $this->user1;

        return $user ;
    }
}
