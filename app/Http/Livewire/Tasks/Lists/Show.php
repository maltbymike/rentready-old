<?php

namespace App\Http\Livewire\Tasks\Lists;

use App\Models\Task;

use Livewire\Component;

class Show extends Component
{

    public $list = [];

    protected $rules = [
        'list.tasks.*.task_status_id' => 'integer',
    ];

    public function render()
    {
        return view('livewire.tasks.lists.show');
    }

    public function changeTaskStatus(Task $task, int $status)
    {
        $task->status()->associate($status);

        // Set status in state so that view will be updated
        $this->list->tasks->find($task->id)->task_status_id = $status;

        $task->save();

        // Update user that task has been changed
        $this->dispatchBrowserEvent('alert',[
            'type' => 'success',
            'message' => __('Status Updated'),
        ]);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function closeOrOpenTask(Task $task)
    {
        // Associate open or closed status to task
        $closeOrOpenStatus = $task->closeOrOpen();
        
        // Set status in state so that view will be updated
        $this->list->tasks->find($task->id)->task_status_id = $closeOrOpenStatus;

        // Persist change
        $task->save();
        
        // Update user that task has been changed
        $this->dispatchBrowserEvent('alert',[
            'type' => 'success',
            'message' => $task->isClosed() ? __('Closed Task') : __('Reopened Task'),
        ]);
    }
}
