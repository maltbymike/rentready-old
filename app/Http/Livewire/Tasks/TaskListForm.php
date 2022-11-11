<?php

namespace App\Http\Livewire\Tasks;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;

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
     * Create New TaskList from submitted information
     */
    public function saveTaskList()
    {   
        $this->resetErrorBag();
    
        $validated = Validator::make($this->state, [
            'id' => 'sometimes|integer',
            'name' => 'required|string|max:100',
            'open' => 'required|integer',
            'closed' => 'required|integer',
        ])->validateWithBag('createTaskList');
        
        $taskList = TaskList::firstOrNew(['id' => $validated['id']]);
        $taskList->name = $validated['name'];
        $taskList->open = $validated['open'];
        $taskList->closed = $validated['closed'];
        
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
