<?php

namespace App\Http\Controllers;

use App\Enums\TeamRoleAssignStatus;
use App\Enums\UserTeamRole as EnumsUserTeamRole;
use App\Models\Team;
use App\Models\User;
use App\Models\UserTeamRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::all();

        $team = null;
        if (Auth::user()->userTeamRoles->count() > 0) {
            $team = Auth::user()->userTeamRoles[0];
        }

        $search = "";

        return view('teams.index', compact('teams', 'team', 'search'))
            ->with(request()->input('page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("teams.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $team = new Team();
        $team->name = $request['name'];
        $team->created_by = Auth::user()->id;
        $team->save();

        $userTeamRole = new UserTeamRole();
        $userTeamRole->user_id = Auth::user()->id;
        $userTeamRole->team_id = $team->id;
        $userTeamRole->role = EnumsUserTeamRole::Owner;
        $userTeamRole->save();

        return redirect()->route('teams.show', $team->id)
            ->with('success', 'Team created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        //$team = Team::find($team->user->userTeamRoles[0]->team_id);

        $users = User::query()
            ->visibleTo(auth()->user())
            ->join('user_team_roles', 'users.id', '=', 'user_team_roles.user_id')
            ->where('user_team_roles.team_id', $team->id)
            ->select('users.id', 'users.name', 'user_team_roles.role')
            ->get();

        $owner = $users->where('role', EnumsUserTeamRole::Owner->value)->first();

        $pendingUsers = $users->where('role', EnumsUserTeamRole::Pending->value);

        $teamUsers = $users
            ->where('role', '!=', EnumsUserTeamRole::Pending->value)
            ->where('role', '!=', EnumsUserTeamRole::Owner->value);

        return view('teams.show', compact('team', 'teamUsers', 'pendingUsers', 'owner'));
    }

    /**
     * Show the form for editing the specified resource.
     *`
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        return view('teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $team->name = $request['name'];

        $team->save();

        return redirect()->route('teams.show', $team->id)
            ->with('success', 'Team updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team) {
        abort(501);
    }

    public function requestAccess(Request $request)
    {
        $search = "";

        $teams = Team::all();

        $team = null;
        if (Auth::user()->userTeamRoles->count() > 0) {
            $team = Auth::user()->userTeamRoles[0];
        }

        $listType = TeamRoleAssignStatus::Unassigned;

        $search = "";

        return view('teams.index', compact('teams', 'team', 'search', 'listType'))
            ->with(request()->input('page'));
    }

    public function processAccessRequest(Request $request)
    {
        $request->validate([
            'team' => 'required'
        ]);

        $userTeamRole = new UserTeamRole();
        $userTeamRole->role = EnumsUserTeamRole::Pending;
        $userTeamRole->user_id = Auth::user()->id;
        $userTeamRole->team_id = $request["team"];
        $userTeamRole->save();

        $team = Team::query()->find($request["team"]);

        return redirect()->route('teams.show', $team->id)
            ->with('success', 'Access request sent successfully.');
    }

    public function removeUser(Team $team, User $user)
    {
        $userTeamRole = UserTeamRole::query()
            ->where('user_id', $user->id)
            ->where('team_id', $team->id)
            ->first();
        $userTeamRole->delete();

        return redirect()->route('teams.show', $userTeamRole->team_id)
            ->with('success', 'User removed successfully.');
    }
}
