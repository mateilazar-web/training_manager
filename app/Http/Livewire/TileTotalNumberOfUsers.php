<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class TileTotalNumberOfUsers extends Component
{
    /** @var string */
    public $position;

    public function mount(string $position)
    {
        $this->position = $position;
    }


    public function render()
    {
        $totalNumberOfUsers = User::query()
            ->count();

        return view('livewire.tile-total-number-of-users', compact('totalNumberOfUsers'));
    }
}
