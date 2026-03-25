<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'difficulty',
        'priority',
        'deadline',
        'done',
        'streaks',
        'frequency',
        'category_id',
        'user_id'
    ];


    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'done' => 'boolean',
            'frequency' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
