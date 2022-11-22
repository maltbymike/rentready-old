<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    use HasFactory;

    public function lists()
    {
        return $this->belongsToMany(TaskList::class)->withPivot('color');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
