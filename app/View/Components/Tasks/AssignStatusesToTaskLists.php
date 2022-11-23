<?php

namespace App\View\Components\Tasks;

use App\Models\TaskStatus;

use Illuminate\View\Component;

class AssignStatusesToTaskLists extends Component
{
    /**
     * Store task statuses
     */
    public $statuses;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->statuses = TaskStatus::all();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.tasks.assign-statuses-to-task-lists');
    }
}
