<?php

namespace App\Http\Livewire\Tasks;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\TaskList;

class ShowTaskLists extends Component
{
    /**
     * Array of task lists.
     *
     * @var array
     */
    public $taskLists = [];

    /**
     * Set event listeners to respond to.
     * 
     * @var array
     */
    protected $listeners = ['reloadTaskLists'];


    public function reloadTaskLists(Request $request)
    {
        // Get task lists that have been assigned to the current team
        $this->taskLists = TaskList::select('id', 'name')
            ->Where('team_id', $request->user()->currentTeam->id)
            ->get()
            ->toArray();
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
