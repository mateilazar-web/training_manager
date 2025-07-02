<?php

namespace App\Http\Livewire;

use App\Models\Team;
use Livewire\Component;

class TileTotalNumberOfTeams extends Component
{
    /** @var string */
    public $position;

    public function mount(string $position)
    {
        $this->position = $position;
    }

    public function render()
    {
        $totalNumberOfTeams = Team::query()
            ->count();

        return view('livewire.tile-total-number-of-teams', compact('totalNumberOfTeams'));
    }
}
