<?php

namespace App\Http\Controllers;

use App\Enums\TeamRoleAssignStatus;
use App\Enums\UserTeamRole as EnumsUserTeamRole;
use App\Models\Team;
use App\Models\User;
use App\Models\UserTeamRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        if (count($authenticatedUser->userTeamRoles) > 0) {
            $team = $authenticatedUser->userTeamRoles[0];
        }

        $search = "";

        $listType = TeamRoleAssignStatus::Admin;

        $data = compact('teams', 'team', 'search', 'listType');
        $data['page'] = request()->input('page');

        return response()->view('teams.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view("teams.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        $team = new Team();
        $team->name = $request['name'];
        $team->created_by = $authenticatedUser->id;
        $team->save();

        $userTeamRole = new UserTeamRole();
        $userTeamRole->user_id = $authenticatedUser->id;
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
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        $users = User::query()
            ->visibleTo($authenticatedUser)
            ->join('user_team_roles', 'users.id', '=', 'user_team_roles.user_id')
            ->where('user_team_roles.team_id', $team->id)
            ->select('users.id', 'users.name', 'user_team_roles.role')
            ->get();

        $owner = $users->where('role', EnumsUserTeamRole::Owner->value)->first();

        $pendingUsers = $users->where('role', EnumsUserTeamRole::Pending->value);

        $teamUsers = $users
            ->where('role', '!=', EnumsUserTeamRole::Pending->value)
            ->where('role', '!=', EnumsUserTeamRole::Owner->value);

        return response()->view('teams.show', compact('team', 'teamUsers', 'pendingUsers', 'owner'));
    }

    /**
     * Show the form for editing the specified resource.
     *`
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        return response()->view('teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\RedirectResponse
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
    public function destroy(Team $team)
    {
        abort(501);
    }

    public function requestAccess(Request $request)
    {
        $search = "";

        $teams = Team::all();

        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        $team = null;
        if (count($authenticatedUser->userTeamRoles) > 0) {
            $team = $authenticatedUser->userTeamRoles[0];
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

        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        $userTeamRole = new UserTeamRole();
        $userTeamRole->role = EnumsUserTeamRole::Pending;
        $userTeamRole->user_id = $authenticatedUser->id;
        $userTeamRole->team_id = $request["team"];
        $userTeamRole->save();

        /** @var Team|null $team */
        $team = Team::query()->find($request["team"]);

        if (!$team) {
            abort(404, "Team not found");
        }
        
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
