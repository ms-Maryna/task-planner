<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Task extends Model
{
    protected $fillable = [
        'title', 'description', 'is_completed',
        'due_date', 'due_time', 'estimated_hours', 'parent_id'
    ];

    protected $casts = [
        'is_completed'    => 'boolean',
        'due_date'        => 'date',
        'estimated_hours' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_id')->orderBy('created_at');
    }

    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }
}
