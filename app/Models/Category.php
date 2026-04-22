<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = ["title" , "color" , "description" , "user_id" , "is_global"] ;

    public function image(){
        return $this->morphOne(Image::class , 'imageable') ;
    }

    public function tasks(){
        return $this->hasMany(Task::class)->where('is_task' , true) ; ;
    }

    public function habits(){
        return $this->hasMany(Task::class)->where('is_task' , false) ;
    }


}
