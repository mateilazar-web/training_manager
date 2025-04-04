<?php

namespace App\Http\Livewire;

use App\Models\Session;
use App\Models\SessionDrill;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

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
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        $session = Session::query()
            ->select('id', 'name')
            ->where('user_id', $authenticatedUser->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!empty($session)) {
            $drills = SessionDrill::query()
                ->select('drills.id', 'drills.name', 'drills.description', 'drills.link', 'session_drills.id as session_drill_id')
                ->join("drills", "drills.id", "=", "session_drills.drill_id")
                ->where("session_id", "=", $session->id)
                ->get();
        } else {
            $drills = [];
        }

        return view('livewire.tile-current-session', compact('session', 'drills'));
    }
}
