<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'open', 'closed'];

    public function tasks() {
        return $this->belongsToMany(Task::class, 'task_lists_tasks', 'task_list_id', 'task_id');
    }
}
