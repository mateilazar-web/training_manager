<?php

namespace App\Http\Livewire;

use App\Models\Session;
use Livewire\Component;
use App\Models\SessionDrill;
use Illuminate\Support\Facades\Auth;

class TileCurrentSession extends Component
{
    /** @var string */
    public $position;

    public function mount(string $position)
    {
        $this->position = $position;
    }


    public function render()
    {
        $session = Session::select('id', 'name')
            ->where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (! empty($session)) {
            $drills = SessionDrill::select('drills.id', 'drills.name', 'drills.description', 'drills.link', 'session_drills.id as session_drill_id')
                ->join("drills", "drills.id", "=", "session_drills.drill_id")
                ->where("session_id", "=", $session->id)
                ->get();
        } else {
            $drills = [];
        }

        return view('livewire.tile-current-session', compact('session', 'drills'));
    }
}
