<?php

namespace SessionGenerator\Services;

use App\Models\Drill;
use App\Models\Session;
use App\Models\SessionDrill;

class Generator
{
    private Session $session;
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function run()
    {
        $drills = Drill::select("drills.id", "drills.name")
            ->join("drill_tags", "drills.id", "=", "drill_tags.drill_id")
            ->join("tags", "drill_tags.tag_id", "=", "tags.id")
            ->where("tags.id", "=", $this->session->tag_id)
            ->inRandomOrder()
            ->take(3)
            ->get();
        foreach ($drills as $drill) {
            $sessionDrill = new SessionDrill();
            $sessionDrill->session_id = $this->session->id;
            $sessionDrill->drill_id = $drill->id;
            $sessionDrill->save();
        }
    }

    public function duplicate()
    {
        $drills = Session::select("drills.id", "drills.name")
            ->join("session_drills", "session_drills.session_id", "=", "sessions.id")
            ->join("drills", "session_drills.drill_id", "=", "drills.id")
            ->where("sessions.id", "=", $this->session->id)
            ->get();

        $duplicateSession = new Session();
        $duplicateSession->name = $this->session->name . " (1)";
        $duplicateSession->tag_id = $this->session->tag_id;
        $duplicateSession->user_id = $this->session->user_id;
        $duplicateSession->save();

        foreach ($drills as $drill) {
            $sessionDrill = new SessionDrill();
            $sessionDrill->session_id = $duplicateSession->id;
            $sessionDrill->drill_id = $drill->id;
            $sessionDrill->save();
        }

        return $duplicateSession->id;
    }
}
