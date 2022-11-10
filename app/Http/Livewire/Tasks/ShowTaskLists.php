<?php

namespace App\Http\Livewire\Tasks;

use Livewire\Component;
use Illuminate\Http\Request;

class ShowTaskLists extends Component
{
    /**
     * Array of task lists.
     *
     * @var array
     */
    public $taskLists = [];


    /**
     * Create the component instance.
     *
     * @param  array $taskLists
     * @return void
     */
    public function __construct($taskLists)
    {
        $this->taskLists = $taskLists;
    }

    /**
     * Render the component
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function render(Request $request)
    {
        return view('livewire.tasks.show-task-lists', [
            'user' => $request->user(),
        ]);
    }
}
