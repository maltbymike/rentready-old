<?php

namespace App\Http\Livewire\Tasks\Lists;

use Livewire\Component;

class Show extends Component
{

    public $list = [];

    public function render()
    {
        return view('livewire.tasks.lists.show');
    }
}
