<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory ;

    protected $fillable = ['content' , 'type', 'visibility' , 'user_id'];

    public function images(){
        return $this->morphMany(Image::class , 'imageable') ;
    }

    public function comments(){
        return $this->hasMany(Comment::class) ;
    }

    public function likes(){
        return $this->hasMany(Like::class) ;
    }

    public function user(){
        return $this->belongsTo(User::class) ;
    }

    public function reports(){
        return $this->hasMany(Report::class) ;
    }
}
