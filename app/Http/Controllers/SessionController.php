<?php

namespace App\Http\Controllers;

use App\Enums\UserTeamRole;
use App\Models\Tag;
use App\Models\Session;
use App\Models\SessionDrill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SessionGenerator\Services\Generator;

class SessionController extends Controller
{
    private string $searchParameter = "";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sessions = Session::select('sessions.id', 'sessions.name', 'tag_id', 'users.name as user_name')
            ->join('users', 'users.id', '=', 'sessions.user_id')
            ->where('user_id', Auth::user()->id)
            ->orderBy('sessions.created_at', 'desc')
            ->paginate(10);


        $teamUsers = User::select('users.id', 'users.name')
            ->join('user_team_roles', 'users.id', '=', 'user_team_roles.user_id')
            ->where('team_id', Auth::user()->userTeamRoles[0]->team_id)
            ->where('user_team_roles.role', '!=', UserTeamRole::Pending->value)
            ->get();

        $userIds[] = Auth::user()->id;

        $search = $this->searchParameter;

        $currentSessionId = Session::select('id')
            ->orderBy('created_at', 'desc')
            ->first();

        return view('sessions.index', compact('sessions', 'search', 'currentSessionId', 'teamUsers', 'userIds'))
            ->with(request()->input('page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::all();
        return view("sessions.create", compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'tag' => 'required',
        ]);

        $session = new Session();
        $session->name = $request['name'];
        $session->tag_id = $request['tag'];
        $session->user_id = Auth::user()->id;

        $session->save();

        return redirect()->route('sessions.show', $session->id)
            ->with('success', 'Session created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Session $session)
    {
        $drills = SessionDrill::select('drills.id', 'drills.name', 'drills.description', 'drills.link', 'session_drills.id as session_drill_id')
            ->join("drills", "drills.id", "=", "session_drills.drill_id")
            ->where("session_id", "=", $session->id)
            ->get();
        return view('sessions.show', compact('session', 'drills'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Session $session)
    {
        $tags = Tag::all();
        return view('sessions.edit', compact('session', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Session $session)
    {
        $request->validate([
            'name' => 'required',
            'tag' => 'required',
        ]);

        $session->name = $request['name'];
        $session->tag_id = $request['tag'];

        $session->save();

        return redirect()->route('sessions.index')
            ->with('success', 'Session updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function duplicate($id)
    {
        $session = Session::find($id);
        $generator = new Generator($session);
        $duplicateSessionId = $generator->duplicate();

        return redirect()->route('sessions.show', $duplicateSessionId);
    }

    public function generate($id)
    {
        $session = Session::find($id);
        $generator = new Generator($session);
        $generator->run();

        return redirect()->route('sessions.show', $session->id);
    }

    public function regenerate($id)
    {
        SessionDrill::where("session_id", $id)->delete();

        return $this->generate($id);
    }

    public function search(Request $request)
    {
        $this->searchParameter = $request->get("name") ?? "";

        $sessions = Session::select('id', 'name', 'tag_id')
            ->where('name', 'like', '%' . $this->searchParameter . '%')
            ->whereIn('user_id', $request["teamUsers"])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $search = $this->searchParameter;

        $currentSessionId = Session::select('id')
            ->orderBy('created_at', 'desc')
            ->first();

        $teamUsers = User::select('users.id', 'users.name')
            ->join('user_team_roles', 'users.id', '=', 'user_team_roles.user_id')
            ->where('team_id', Auth::user()->userTeamRoles[0]->team_id)
            ->get();

        $userIds = $request->get("teamUsers");

        return view('sessions.index', compact('sessions', 'search', 'currentSessionId', 'teamUsers', 'userIds'))
            ->with(request()->input('page'));
    }
}
