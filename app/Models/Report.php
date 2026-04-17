<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /** @use HasFactory<\Database\Factories\ReportFactory> */
    use HasFactory;

    protected $fillable = ['description' , 'type', 'post_id' , 'user_id' , 'is_confirmed'];


    public function user(){
        return $this->belongsTo(User::class) ;
    }

    public function post(){
        return $this->belongsTo(Post::class) ;
    }

}
