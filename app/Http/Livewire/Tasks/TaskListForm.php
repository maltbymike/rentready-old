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
    public $state = [
        'showListForm' => false,
        'statuses' => false,
    ];

    /**
     * The current task list.
     *
     * @var integer
     */
    public $listId;

    /**
     * Set event listeners to respond to.
     * 
     * @var array
     */
    protected $listeners = [
        'loadTaskList',
        'modal-closed' => 'modalClosed',
    ];

    /**
     * The url query parameters
     * 
     * @var array
     */
    protected $queryString = [
        'listId' => ['except' => '', 'as' => 'l'],
    ];

    /**
     * Clear form data
     */
    public function clear()
    {
        $this->reset();
    }

    public function mount(Request $request)
    {
        // If a task has been set in the url load it
        if (request('l') !== null) {
            $this->loadTaskList(request('l'));
        }
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
            'add_status' => 'required',
            'add_status.*' => 'integer',
            'default_status' => 'required|integer',
            'closed_status' => 'required|integer',
        ],
        [   
            'add_status.required' => __('At least one active status is required'),
            'add_status.*.integer' => __('Use checkbox to set active status'),
        ])
        ->validateWithBag('createTaskList');
            
        // If id is set find and update tasklist otherwise create a new tasklist
        if (isset($validated['id'])) {

            $taskList = TaskList::find($validated['id']);

        } else {

            $taskList = new TaskList;

            // Assign tasklist to current team
            $taskList->team()->associate($request->user()->currentTeam->id);

        }

        // Assign submitted values to tasklist
        $taskList->name = $validated['name'];
        $taskList->open = $validated['default_status'];
        $taskList->closed = $validated['closed_status'];

        // Assign statuses to tasklist
        $taskList->statuses()->sync($validated['add_status']);

        // Save to database
        $taskList->save();

        $this->emit('created');
        $this->emit('reloadTaskLists');
    }

    /**
     * Load tasklist by id
     */
    public function loadTaskList($id)
    {
        $taskList = TaskList::with('statuses')->find($id);

        $this->reset();

        $this->state = $taskList->toArray();
        
        $this->state['showListForm'] = true;

        // Create array of currently assigned statuses
        foreach ($taskList->statuses as $status) {
            $statusArray[$status->id] = $status->id;
        }

        // Mark currently assigned statuses to state
        $this->state['add_status'] = $statusArray;

        // Set open and closed values
        $this->state['default_status'] = $taskList->open;
        $this->state['closed_status'] = $taskList->closed;

        $this->listId = $taskList->id;
    }

    /**
     * Reset state when modal is closed
     * 
     * @return void
     */
    public function modalClosed()
    {
        $this->reset('state', 'listId');
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
