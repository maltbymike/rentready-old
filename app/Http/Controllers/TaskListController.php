<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TaskList;

class TaskListController extends Controller
{
    /**
     * Send task lists to component.
     *
     * @var array
     */
    public $taskLists = [];

    /**
     * Show the task list management screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        // Get task lists that have been assigned to the current team
        $this->taskLists = TaskList::select('id', 'name')
                                ->Where('team_id', $request->user()->currentTeam->id)
                                ->withCount('tasks')
                                ->get()
                                ->toArray();
        
        // Render the component
        return view('tasks.task-lists', [
            'taskLists' => $this->taskLists,
        ]);
    }
}
