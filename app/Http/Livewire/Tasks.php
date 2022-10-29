<?php

namespace App\Http\Livewire;

use App\Models\Task;
use App\Models\TaskStatus;

use Livewire\Component;

use Alert;

class Tasks extends Component
{
    public $updateMode;
    public $taskStatuses;
    public $taskStatusClosed;
    public $taskStatusOpen;

    public $currentTask = [];
    public $currentTaskId;
    public $tasks;
    public $tasksToday;
    public $tasksTomorrow;
    public $tasksOverdue;
    public $tasksFuture;
    public $tasksWithNoDueDate;

    public $i = 1;
    public $subtasksNew = [];
    public $subtask;
    public $subtaskOrderChanged = false;

    public $showClosed = false;

    public $showTaskDialog = false;

    protected $queryString = [
        'currentTaskId' => ['except' => '', 'as' => 'id'],
        'showClosed' => ['except' => false, 'as' => 'completed'],
    ];

    protected $rules = [
        'currentTask.id' => 'sometimes|integer',
        'currentTask.name' => 'required|min:5',
        'currentTask.details' => 'nullable|string',
        'currentTask.statusId' => 'required|integer',
        'currentTask.dateDue' => 'nullable|date|after_or_equal:currentTask.dateStart',
        'currentTask.dateStart' => 'nullable|date',
        'currentTask.subtasks.*.name' => 'required|min:5',
        'subtask.*.name' => 'required',
    ];

    protected $validationAttributes = [
        'currentTask.id' => 'Task ID',
        'currentTask.name' => 'Task Name',
        'currentTask.details' => 'Task Details',
        'currentTask.statusId' => 'Task Status',
        "currentTask.dateDue" => 'Due Date',
        'currentTask.dateStart' => 'Start Date',
        'currentTask.subtasks.*.name' => 'Subtask Name',
        'subtask.*.name' => 'Subtask Name',
    ];

    public function edit($id)
    {
        $this->updateMode = true;
        $this->resetValidation();

        $task = Task::with('status', 'children')->find($id);
        $this->currentTask['id'] = $task->id;
        $this->currentTask['name'] = $task->name;
        $this->currentTask['statusId'] = $task->status->id;
        $this->currentTask['dateStart'] = $task->date_start;
        $this->currentTask['dateDue'] = $task->date_due;
        $this->currentTask['details'] = $task->details;
        $this->currentTask['subtasks'] = $task->children->toArray();

        $this->currentTaskId = $task->id; // Until livewire queryString supports multidimensional arrays properly we need to set it as a separate public variable

        $this->showTaskDialog = true;
    }

    protected function getTasksList()
    {
        $tasks = Task::with('status', 'children');
        $tasks = $tasks->where('parent_id', '=', null);
        
        if ($this->showClosed != true)
            $tasks = $tasks->where('task_status_id', '!=', $this->taskStatusClosed);
        
        $this->tasks = $tasks->get();

        $this->tasksToday = $this->getTodaysTasks();
        $this->tasksTomorrow = $this->getTomorrowsTasks();
        $this->tasksOverdue = $this->getOverdueTasks();
        $this->tasksFuture = $this->getFutureTasks();
        $this->tasksWithNoDueDate = $this->getTasksWithNoDueDate();
    }

    protected function getOverdueTasks()
    {
        return $this->tasks
            ->where('date_due', '<', now('America/Toronto')->format("Y-m-d"))
            ->where('date_due', '!=', NULL);
    }

    protected function getTodaysTasks()
    {
        return $this->tasks->where('date_due', '>=', now('America/Toronto')->format("Y-m-d"))
                           ->filter(function($value, $key) {
                                if ($value->date_start <= now('America/Toronto')->format("Y-m-d") and $value->date_start != NULL) {
                                    return true;
                                }
                           })
                           ->where('date_start', '<=', now('America/Toronto')->format("Y-m-d"));
    }

    protected function getTomorrowsTasks()
    {
        return $this->tasks->where('date_due', now('America/Toronto')->addDays(1)->format("Y-m-d"))
                           ->filter(function($value, $key) {
                                if ($value->date_start > now('America/Toronto')->format("Y-m-d") or $value->date_start == NULL) {
                                    return true;
                                }
                           });
    }

    protected function getFutureTasks()
    {
        return $this->tasks->where('date_due', '>', now('America/Toronto')->addDays(1)->format("Y-m-d"))
                           ->filter(function($value, $key) {
                                if ($value->date_start > now('America/Toronto')->format("Y-m-d") or $value->date_start == NULL) {
                                    return true;
                                }
                           });
    }

    protected function getTasksWithNoDueDate()
    {
        return $this->tasks->where('date_due', NULL);
    }

    public function handleSortOrderChange($sortOrder)
    {
        $currentSubtasks = collect($this->currentTask['subtasks']);

        $this->currentTask['subtasks'] = collect($sortOrder)
            ->map(function ($id) use ($currentSubtasks) {
                return $currentSubtasks->firstWhere('id', $id);
            })
            ->toArray();
        
        $this->subtaskOrderChanged = true;
    }

    public function mount()
    {
        $this->taskStatuses = TaskStatus::all();
        $this->taskStatusClosed = $this->taskStatuses->firstWhere('name', 'Closed')->id;
        $this->taskStatusOpen = $this->taskStatuses->firstWhere('name', 'Open')->id;
    
        $this->getTasksList();

        $this->currentTask['statusId'] = $this->taskStatusOpen;

        // If id is set in the request load the task
        if (request('id') !== null) {
            $this->edit(request('id'));
        }

        // If completed is set in the request set the flag to show closed tasks
        if (request('completed') == true) {
            $this->showClosed = true;
        }
    }

    public function render()
    {
        return view('livewire.tasks');
    }

    public function resetFormFields()
    {
        $this->resetValidation();
        $this->resetExcept('taskStatuses', 'taskStatusClosed', 'taskStatusOpen');
        $this->getTasksList();
        $this->currentTask['statusId'] = $this->taskStatusOpen;
    }

    public function save($id = null, $clear = false)
    {
        $this->validate();

        // If id is set get the task from the database, otherwise create new task
        if ($id != null) {
            $task = Task::with('children')->find($id);
        } else {
            $task = New Task;
        }

        // Determine which fields were entered and set the appropriate values on the task
        foreach ($this->currentTask as $key => $value) {
            switch ($key):
                case 'name':
                    $task->name = $value;
                    break;
                case 'dateStart':
                    $task->date_start = $value;
                    break;
                case 'dateDue':
                    $task->date_due = $value;
                    break;
                case 'details':
                    $task->details = $value;
                    break;
            endswitch;
        }

        // Set relationships
        $task->status()->associate($this->currentTask['statusId']);
                
        // Save task
        $task->save();

        if ($this->subtaskOrderChanged == true) {
            
            foreach ($this->currentTask['subtasks'] as $key => $value) {
                $subtaskToChangeOrder = Task::find($value['id']);
                $subtaskToChangeOrder->sort_order = $key;
                $subtaskToChangeOrder->save();
            }
        
        }

        // Create new subtasks
        $nextSortOrder = count($this->currentTask['subtasks']); 

        if (isset($this->subtask)) {
            foreach ($this->subtask as $s) {

                // Create new task on model
                $subtask = New Task;
                
                // Determine which fields were entered and set the appropriate values on the subtask
                foreach ($s as $key => $value) {
                    switch ($key):
                        case 'name':
                            $subtask->name = $value;
                            break;
                    endswitch;
                }

                // Set Sort Order
                $subtask->sort_order = $nextSortOrder;
                $nextSortOrder++;

                // Set relationships
                $subtask->parent()->associate($task->id);
                $subtask->status()->associate($this->taskStatusOpen);

                // Save subtask
                $subtask->save();
            }
        }

        // Repopulate tasks
        $this->getTasksList();

        // Reset form fields if the option was set
        // if ($clear == true) {
            $this->resetFormFields();
        // }

        // If we created a new task then load the newly created 
        // task, otherwise clicking save again will create another new task
        if ($clear != true) {
        // if ($clear != true && $id == null) {
            $this->edit($task->id);
        }
        
        // Send alert message
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=> $task->name . ": Saved!"
        ]);
    }

    public function subtasksAdd($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->subtasksNew, $i);
        $this->emit('set-focus-on-new-subtask', $i);
    }

    public function subtasksRemove($i)
    {
        unset($this->subtasksNew[$i]);
    }

    public function toggleShowClosed()
    {
        // Toggle $showClosed
        $this->showClosed = $this->showClosed == true ? false : true;

        // Repopulate tasks
        $this->getTasksList();
    }

    public function updateTaskStatusClosed($id)
    {
        // Get task to update
        $task = Task::with('parent', 'status')->find($id);

        // Decide if we are opening or closing the task
        if (isset($task->status->id)) {
            if ($task->status->id != $this->taskStatusClosed) {
                $changeStatusTo = $this->taskStatusClosed;
                $changeStatusText = 'Closed';
            } else {
                $changeStatusTo = $this->taskStatusOpen;
                $changeStatusText = 'Open';
            }
        } else {
            $changeStatusTo = $this->taskStatusClosed;
            $changeStatusText = 'Closed';
        }
        $task->status()->associate($changeStatusTo);
        
        // Save task
        $task->save();

        // If current task has a parent refresh the subtask list
        if ($task->parent && $this->currentTask['id'] == $task->parent->id) {
            $this->currentTask['subtasks'] = $task->parent->children;
        }

        // If this is the current task then refresh it's status id
        if (isset($this->currentTask['id']) && $id == $this->currentTask['id']) {
            $this->currentTask['statusId'] = $changeStatusTo;
        }

        // Repopulate Tasks
        $this->getTasksList();

        // Update user that task has been changed
        $this->dispatchBrowserEvent('alert',[
            'type' => 'success',
            'message' => $task->name . ": " . $changeStatusText . "!"
        ]);
    }

}