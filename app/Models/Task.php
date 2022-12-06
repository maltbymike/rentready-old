<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $casts = [
        'closed_at' => 'date',
    ];

    protected $fillable = ['name', 'details'];


    /**
     * @return \App\Models\Task
     */
    public function children() {

        return $this->hasMany(Task::class, 'parent_id')->orderBy('sort_order');

    }


    /**
     * @return int // task_status_id of task list's closed or open value
     */
    public function closeOrOpen() 
    {

        // Toggle closed_at
        $this->isClosed()
            ? $this->setStatusOpened()
            : $this->setStatusClosed();

        // Find first task list assigned to this task
        $list = $this->getPrimaryTaskList();

        // Get open or closed status from task list
        $closeOrOpenStatus = $this->closed_at != null ? $list->closed : $list->open;

        // Associated task with status
        $this->status()->associate($closeOrOpenStatus);
        
        return $closeOrOpenStatus;
    }


    /**
     * Get the difference between the current task date due and the next date it should repeat
     * 
     * @return int
     */
    public function getNextDateOffset() {

        $currentDueDate = Carbon::parse($this->date_due);

        switch ($this->repeats) {
                
            case 'daily':
                $nextDate = now()->addDay();
                break;

            case 'weekly':
                $nextDate = now()->next($currentDueDate->dayName);
                break;

            case 'monthly':
                $nextDate = now()->addMonth();
                break;

            case 'yearly':
                $nextDate = now()->addYear();
                break;

            case 'weekdays':
                $nextDate = now();
                do {
                    $nextDate = $nextDate->addDay();
                } while (! $nextDate->isWeekday());
                break;

        }

        $dateOffset = null;

        if (isset($nextDate)) {

            $dateOffset = $currentDueDate->diff($nextDate);

        }

        return $dateOffset;

    }

    public function getPrimaryTaskList() {

        return $this->lists->first();

    }

    /**
     * @return boolean
     */
    public function isClosed() {

        return $this->closed_at !== null;

    }

    /**
     * @return \App\Models\TaskList
     */
    public function lists() {

        return $this->belongsToMany(TaskList::class, 'task_lists_tasks', 'task_id', 'task_list_id');

    }

    /**
     * @return \App\Models\Task
     */
    public function parent() {

        return $this->belongsTo(Task::class, 'parent_id');

    }


    /**
     * @return \App\Models\Task
     */
    public function replicateTask() {

        $openStatus = $this->getPrimaryTaskList()->open;

        $clone = $this->replicate();

        $clone->push();

        $clone->status()->associate($openStatus);

        foreach($this->children as $child) {

            $child->status()->associate($openStatus);

            $clone->children()->create($child->toArray());

        }

        return $clone;
    }

    /**
     * @return null
     */
    public function setStatusClosed()
    {

        // Process recurrance if necessary
        if ($this->repeats != null) {

            // Copy current task
            $newTask = $this->replicateTask();

            // Get the date offset between the next due date and the current due date
            $dateOffset = $this->getNextDateOffset();

            // Create Carbon/Carbon instance based on due date
            $currentDueDate = Carbon::parse($this->date_due);
            
            // Set date due on new task
            $newTask->date_due = $currentDueDate->addDays($dateOffset->days)->toDateString();


            // Set new start date
            if (isset($this->date_start)) {
            
                // Create Carbon/Carbon instance based on start date
                $currentStartDate = Carbon::parse($this->date_start);

                // Set start date on new task
                $newTask->date_start = $currentStartDate->addDays($dateOffset->days)->toDateString();

            }

            // Add newTask to task lists
            $listsArray = $this->lists->pluck('id')->toArray();
            $newTask->lists()->sync($listsArray);

            // remove recurrance from current task so that if it is 
            // ever reopened only the new task will repeat 
            $this->repeats = null;

        } 

        // Close task
        $this->closed_at = now();

        // Save changes to newTask;
        $newTask->save();

    }

    /**
     * @return null
     */
    public function setStatusOpened()
    {

        $this->closed_at = null;

    }

    /**
     * @return \App\Models\TaskStatus
     */
    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'task_status_id');
    }

}
