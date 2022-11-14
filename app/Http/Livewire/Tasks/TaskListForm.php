<?php

namespace App\Http\Livewire\Tasks;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\TaskList;

class TaskListForm extends Component
{
    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    /**
     * Set event listeners to respond to.
     * 
     * @var array
     */
    protected $listeners = ['loadTaskList'];


    /**
     * Clear form data
     */
    public function clear()
    {
        $this->reset();
    }

    /**
     * Create New TaskList from submitted information
     * 
     * @param Illuminate\Http\Request   $request
     */
    public function saveTaskList(Request $request)
    {   
        $this->resetErrorBag();
    
        // Validate
        $validated = Validator::make($this->state, [
            'id' => 'sometimes|integer',
            'name' => 'required|string|max:100',
            'open' => 'required|integer',
            'closed' => 'required|integer',
        ])->validateWithBag('createTaskList');
        
        // If id is set find and update tasklist otherwise create a new tasklist
        if (isset($validated['id'])) {
            $taskList = TaskList::find($validated['id']);
        } else {
            $taskList = new TaskList;
        }

        // Assign submitted values to tasklist
        $taskList->name = $validated['name'];
        $taskList->open = $validated['open'];
        $taskList->closed = $validated['closed'];

        // Assign tasklist to current team
        $taskList->team()->associate($request->user()->currentTeam->id);
        
        // Save to database
        $taskList->save();

        $this->emit('created');
        $this->emit('reloadTaskLists');
    }

    /**
     * Load tasklist by id
     */
    public function loadTaskList(TaskList $taskList)
    {
        $this->reset();

        $this->state = $taskList->toArray();
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.tasks.task-list-form');
    }
}
