<?php

namespace App\View\Components\tasks;

use Illuminate\View\Component;

class taskGroup extends Component
{
    public $tasks;
    public $statuses;
    public $closed;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($tasks, $closed, $statuses)
    {
        $this->closed = $closed;
        $this->tasks = $tasks;
        $this->statuses = $statuses;
    }

    /**
     * Get the colour assigned to the task status
     * 
     * @return string
     */
    public function getStatusColour()
    {
        
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
