<?php

namespace App\Http\Livewire\Tasks\Lists;

use App\Models\Task;

use Livewire\Component;

class Show extends Component
{

    public $list = [];

    protected $rules = [
        'list.tasks.*.task_status_id' => 'integer',
        'list.tasks.*.is_closed' => 'boolean',
    ];

    public function mount()
    {

        $this->processTaskList();

    }

    public function processTaskList()
    {

        foreach ($this->list->tasks as $task) {

            $task->is_closed = $task->isClosed();

        }

    }

    public function render()
    {

        return view('livewire.tasks.lists.show');

    }

    public function changeTaskStatus(Task $task, int $status)
    {

        // Set Status
        $task->setStatus($status);

        // Persist changes
        $task->save();

        // Set status in state so that view will be updated
        $this->list->tasks->find($task->id)->task_status_id = $status;
        $this->list->tasks->find($task->id)->is_closed = $task->isClosed();

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

        // Persist change
        $task->save();

        // Set status in state so that view will be updated
        $this->list->refresh();
        $this->processTaskList();
        
        // Update user that task has been changed
        $this->dispatchBrowserEvent('alert',[
            'type' => 'success',
            'message' => $task->isClosed() ? __('Closed Task') : __('Reopened Task'),
        ]);

    }

}
