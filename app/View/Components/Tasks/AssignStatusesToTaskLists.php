<?php

namespace App\View\Components\Tasks;

use App\Models\TaskStatus;

use Illuminate\View\Component;

class AssignStatusesToTaskLists extends Component
{
    /**
     * Store task statuses from database and active statuses or current list
     */
    public $statuses;
    public $currentListStatuses;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($statuses)
    {
        $this->statuses = TaskStatus::all();

        // Create array of currently assigned statuses
        foreach ($statuses as $status) {
            $statusArray[] = $status['id'];
        }
        
        $this->currentListStatuses = $statusArray;
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
