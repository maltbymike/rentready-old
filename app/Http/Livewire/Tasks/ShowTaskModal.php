<?php

namespace App\Http\Livewire\Tasks;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\TaskStatus;

use Illuminate\Http\Request;

use Livewire\Component;

class ShowTaskModal extends Component
{
    protected $listeners = [
        'showTask',
        'modal-closed' => 'modalClosed',
    ];

    protected $queryString = [
        'currentTaskId' => ['except' => '', 'as' => 't'],
    ];

    protected $rules = [
        'currentTask.id' => 'sometimes|integer',
        'currentTask.name' => 'required|min:5',
        'currentTask.details' => 'nullable|string',
        'currentTask.statusId' => 'required|integer',
        'currentTask.task_repeats' => 'nullable|in:daily,weekly,monthly,yearly,workdays,custom',
        'currentTask.date_due' => 'nullable|date|after_or_equal:currentTask.date_start',
        'currentTask.children.*.name' => 'required|min:5',
        'currentTask.subtasknew.name' => 'sometimes|min:5',
    ];

    protected $validationAttributes = [
        'currentTask.id' => 'Task ID',
        'currentTask.name' => 'Task Name',
        'currentTask.details' => 'Task Details',
        'currentTask.statusId' => 'Task Status',
        'currentTask.task_repeats' => 'Task Repeats',
        "currentTask.date_due" => 'Due Date',
        'currentTask.date_start' => 'Start Date',
        'currentTask.children.*.name' => 'Subtask Name',
        'currentTask.subtasknew.name' => 'Subtask Name',
    ];

    public $showTaskModal = false;
    public $taskStatuses;
    public $taskStatusClosed;

    public $currentTask = [];
    public $currentTaskId;
    public $taskLists = [];

    public function assignTaskToList(TaskList $taskList)
    {
        // Associate tasklist with task
        $taskList->tasks()->syncWithoutDetaching($this->currentTaskId);
        $taskList->save();

        // Send alert message
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=> "Assigned task to " . $taskList->name
        ]);
    }

    public function changeTaskStatus(Task $task, int $status)
    {

        // Set status
        $task->setStatus($status);

        // Persist change
        $task->save();

        // Set status in state so that view will be updated
        $this->currentTask = $task->toArray();
        $this->currentTask['is_closed'] = $task->isClosed();

        // Update user that task has been changed
        $this->dispatchBrowserEvent('alert',[
            'type' => 'success',
            'message' => __('Status Updated'),
        ]);
    
    }

    public function handleSortOrderChange($sortOrder)
    {
        $currentSubtasks = collect($this->currentTask['children']);

        // Reorder the subtasks on the current model binding
        $this->currentTask['children'] = collect($sortOrder)
            ->map(function ($id) use ($currentSubtasks) {
                return $currentSubtasks->firstWhere('id', $id);
            })
            ->toArray();

        // Get all subtasks for current task from database
        $subtasks = Task::select('id', 'sort_order')
            ->where('parent_id', $this->currentTask['id'])
            ->get();

        // Make an array where key is the subtask id and value is the sort order
        $sortOrder = array_flip($sortOrder);

        // Update the sortorder on the database
        foreach ($subtasks as $subtask) {
            $subtask->sort_order = $sortOrder[$subtask->id];
            $subtask->update();
        };

        // Send alert message
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=> "Subtasks Reordered"
        ]);  
    }

    public function modalClosed()
    {
        $this->reset('currentTask', 'currentTaskId');
    }

    public function mount(Request $request)
    {
        // Get task lists that have been assigned to the current team
        $this->taskLists = TaskList::select('id', 'name')
                                ->Where('team_id', $request->user()->currentTeam->id)
                                ->get()
                                ->toArray();

        // Get task statuses
        $this->taskStatuses = TaskStatus::all();

        // If a task has been set in the url load it
        if (request('t') !== null) {
            $this->showTask(request('t'));
        }
    }

    public function removeTaskFromList(TaskList $taskList)
    {
        // Remove task from tasklist
        $taskList->tasks()->detach($this->currentTaskId);
        $taskList->save();

        // Send alert message
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=> "Removed task from " . $taskList->name
        ]);        
    }
    
    public function render()
    {
        return view('livewire.tasks.show-task-modal');
    }

    public function saveSubtask($parentId = null)
    {

        // Create new task on model
        $subtask = New Task;
        
        // Determine which fields were entered and set the appropriate values on the subtask
        foreach ($this->currentTask['subtasknew'] as $key => $value) {
            switch ($key):
                case 'name':
                    $subtask->name = $value;
                    break;
            endswitch;
        }

        // Set Sort Order
        $subtask->sort_order = count($this->currentTask['children']);

        // Set relationships
        $subtask->parent()->associate($parentId);

        // Save subtask
        $subtask->save();

        // Reload the subtasks including the newly added one
        $this->currentTask['children'] = $subtask->parent->children;

        unset($this->currentTask['subtasknew']);

        // Send alert message
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=> "Added subtask: " . $subtask->name
        ]);   
    }

    public function showTask($id)
    {
        $task = Task::with('status', 'children', 'lists')->find($id);

        $this->currentTask = $task->toArray();
        $this->currentTaskId = $task->id;
        
        $this->showTaskModal = true;
    }

    public function updatedCurrentTask($value, $propertyName)
    {
        // Validate updated field
        $this->validateOnly('currentTask.' . $propertyName);

        // Set field name of updated value
        $update = false;

        switch ($propertyName) {
            case 'name':
                $update['field'] = 'name';
                $update['text'] = 'Updated: Name';
                break;
            case 'date_start':
                $update['field'] = 'date_start';
                $update['text'] = 'Updated: Start Date';
                break;
            case 'date_due':
                $update['field'] = 'date_due';
                $update['text'] = 'Updated: Due Date';
                break;
            case 'details':
                $update['field'] = 'details';
                $update['text'] = 'Autosave: Task Details';
                break;
            case 'repeats':
                $update['field'] = 'repeats';
                $update['text'] = 'Updated: Recurrance';
                break;
            case 'task_status_id':
                $update['field'] = 'task_status_id';
                $update['text'] = "Updated: Task Status";
                break;
        }

        // Update if needed
        if ($update) {
            $task = Task::find($this->currentTaskId);
            $task->{$update['field']} = $value;
            
            if($task->save()) {
                // Send alert message
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=> $update['text']
                ]);
            }
        }
    }

    public function updateTaskStatusClosed($id)
    {
        // Get task to update
        $task = Task::with('parent', 'status')->find($id);

        // Decide if we are opening or closing the task
        if ($task->isClosed()) {
            $task->closed_at = null;
            $updateText = __('Task Opened');
        } else {
            $task->closed_at = now();
            $updateText = __('Task Closed');
        }
        // Save task
        $task->save();

        // Reload task to reflect changes
        $this->showTask($this->currentTask['id']);

        // Update user that task has been changed
        $this->dispatchBrowserEvent('alert',[
            'type' => 'success',
            'message' => $updateText,
        ]);
    }
}
