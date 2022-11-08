<?php

namespace App\Http\Livewire\Tasks;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;

use App\Models\TaskList;

class CreateTaskListForm extends Component
{
    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];


    public function createTaskList()
    {   
        $this->resetErrorBag();
    
        $validated = Validator::make($this->state, [
            'name' => 'required|string|max:100',
            'open' => 'required|integer',
            'closed' => 'required|integer',
        ])->validateWithBag('createTaskList');

        TaskList::create($validated);

        $this->emit('created');
    }


    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.tasks.create-task-list-form');
    }
}
