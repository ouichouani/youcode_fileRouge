<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
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
        'role',
        'score'

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
        return $this->hasMany(Task::class)->where('is_task' , true);
    }

    public function habits()
    {
        return $this->hasMany(Task::class)->where('is_task' , false);
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

    public function reports(){
        return $this->hasMany(Report::class);
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }
}
