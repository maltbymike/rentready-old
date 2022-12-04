<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $casts = [
        'closed_at' => 'date',
    ];

    protected $fillable = ['name', 'details'];

    public function children() {
        return $this->hasMany(Task::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * @return int // task_status_id of task list's closed or open value
     */
    public function closeOrOpen() {

        // Toggle closed_at
        $this->closed_at = $this->isClosed() ? null : now();

        // Find first task list assigned to this task
        $list = $this->lists->first();

        // Get open or closed status from task list
        $closeOrOpenStatus = $this->closed_at != null ? $list->closed : $list->open;

        // Associated task with status
        $this->status()->associate($closeOrOpenStatus);
        
        return $closeOrOpenStatus;
    }

    public function closedOrStatus() {
        $status = $this->isClosed() ? 5 : $this->task_status_id;
        return TaskStatus::find($status);
    }

    public function isClosed() {
        return $this->closed_at !== null;
    }

    public function lists() {
        return $this->belongsToMany(TaskList::class, 'task_lists_tasks', 'task_id', 'task_list_id');
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
