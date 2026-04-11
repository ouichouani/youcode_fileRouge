<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /** @use HasFactory<\Database\Factories\LogFactory> */
    use HasFactory;

    
    protected $fillable = ['task_id', 'completed_date', 'notes'];
    
    
    protected $casts = [
        'completed_date' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

}


