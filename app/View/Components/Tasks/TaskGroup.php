<?php

namespace App\View\Components\Tasks;

use Illuminate\View\Component;

class TaskGroup extends Component
{
    public $tasks;
    public $closed;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($tasks, $closed)
    {
        $this->tasks = $tasks;
        $this->closed = $closed;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.tasks.task-group');
    }
}
