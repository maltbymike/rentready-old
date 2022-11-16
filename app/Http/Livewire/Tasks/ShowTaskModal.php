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
    ];

    protected $queryString = [
        'currentTaskId' => ['except' => '', 'as' => 't'],
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
        $taskList->tasks()->attach($this->currentTaskId);
        $taskList->save();

        // Send alert message
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=> "Assigned task to " . $taskList->name . " List"
        ]);
    }

    public function mount(Request $request)
    {
        // Get task lists that have been assigned to the current team
        $this->taskLists = TaskList::select('id', 'name')
                                ->Where('team_id', $request->user()->currentTeam->id)
                                ->get()
                                ->toArray();

        $this->taskStatuses = TaskStatus::all();
    }

    public function render()
    {
        return view('livewire.tasks.show-task-modal');
    }

    public function showTask($id)
    {
        $task = Task::with('status', 'children', 'lists')->find($id);

        $this->currentTask = $task->toArray();
        $this->currentTaskId = $task->id;
        
        $this->showTaskModal = true;
    }
}
