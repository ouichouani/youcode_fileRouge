<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'last_seen_at' ,
        'role',
        'score',
        'social_id',
        'social_type',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class)->where('is_task', true);
    }

    public function habits()
    {
        return $this->hasMany(Task::class)->where('is_task', false);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function sentRequests()
    {
        return $this->hasMany(FriendRequest::class, 'sender_id');
    }

    public function receivedRequests()
    {
        return $this->hasMany(FriendRequest::class, 'receiver_id');
    }

    public function is_frend_with(User $user): bool
    {
        return $this->sentRequests()->where('receiver_id', $user->id)->where('status', 'accepted')->exists() ||
            $this->receivedRequests()->where('sender_id', $user->id)->where('status', 'accepted')->exists();
    }

    // used in factories

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // used for JWT

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // used for social login

    public function oauth()
    {
        return $this->hasMany(Oauth::class);
    }

    // video relationship

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    // group relationship 

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'groups_users', 'user_id', 'group_id');
    }

    public function ownedGroups()
    {
        return $this->hasMany(Group::class, 'owner_id');
    }

    public function conversations()
    {
        return Conversation::where('user1_id' , $this->id)->orWhere('user2_id' , $this->id) ;
    }
}
