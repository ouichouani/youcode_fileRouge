<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /** @use HasFactory<\Database\Factories\LogFactory> */
    use HasFactory;

    
    protected $fillable = ['user_id' , 'task_id', 'completed_date', 'done', 'notes'];
    
    
    protected $casts = [
        'completed_date' => 'datetime',
        'done' => 'boolean',
    ];

}


