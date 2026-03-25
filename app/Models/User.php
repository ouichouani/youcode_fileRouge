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
        return $this->hasMany(Task::class)->whereJsonContains('frequency' , 'OneTime');
    }

    public function habits()
    {
        return $this->hasMany(Task::class)->whereJsonDoesntContain('frequency' , 'OneTime');
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

    // used in factories

    public function reports(){
        return $this->hasMany(Report::class);
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }
}
