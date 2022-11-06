<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'details'];

    public function children() {
        return $this->hasMany(Task::class, 'parent_id')->orderBy('sort_order');
    }

    public function parent() {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function replicateTask($statusId) {
        $clone = $this->replicate();
        $clone->push();
        $clone->status()->associate($statusId);

        foreach($this->children as $child) {
            $child->status()->associate($statusId);
            $clone->children()->create($child->toArray());
        }

        return $clone;
    }

    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'task_status_id');
    }

}
