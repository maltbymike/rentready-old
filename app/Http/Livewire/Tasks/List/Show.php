<?php

namespace App\Http\Livewire\Tasks\List;

use Livewire\Component;

class Show extends Component
{

    public $list = [];

    public function render()
    {
        return view('livewire.tasks.list.show');
    }
}
