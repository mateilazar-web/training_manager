<?php

namespace App\Http\Controllers;

use App\Enums\UserTeamRole;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use App\Models\UserTeamRole as ModelsUserTeamRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = [];

        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        if ($authenticatedUser->role_id == "1") {
            $users = User::all();
        } else {
            if (empty($authenticatedUser->userTeamRoles)) {
                abort(404, "User has no team roles");
            }

            if ($authenticatedUser->userTeamRoles[0]->role == "Owner") {
                $team = Team::query()->find($authenticatedUser->userTeamRoles[0]->team_id);

                if (!$team instanceof Team) {
                    abort(404, "Team not found");
                }

                $users = DB::table('users')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->join('user_team_roles', 'users.id', '=', 'user_team_roles.user_id')
                    ->where('user_team_roles.team_id', $team->id)
                    ->select('users.id', 'users.name', 'user_team_roles.role', "roles.name as role_name")
                    ->get();
            }
        }


        return view('users.index', compact('users'))
            ->with(request()->input('page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(501);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort(501);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $teamRole = $user->userTeamRoles[0];

        return view('users.show', compact('user', 'teamRole'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $teams = Team::all();
        $teamRoles = array_map(fn ($teamRole) => $teamRole->value, UserTeamRole::cases());

        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        $canEditUserRole = false;
        if ($authenticatedUser->role->name == "Admin") {
            $canEditUserRole = true;
        } else {
            if ($authenticatedUser->userTeamRoles[0]->role == UserTeamRole::Owner->value) {
                $canEditUserRole = true;
            }
        }


        return view('users.edit', compact('user', 'roles', 'teams', 'teamRoles', 'canEditUserRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (!isset($request["role"])) {
            $request["role"] = $user->role_id;
        }

        $request->validate([
            'name' => 'required',
            'role' => 'required'
        ]);

        if (!isset($user->userTeamRoles[0])) {
            $teamRole = new ModelsUserTeamRole();
            $teamRole->team_id = $request["team"];
            $teamRole->role = UserTeamRole::Pending;

            $user->userTeamRoles()->save($teamRole);
        } else {
            $teamRole = $user->userTeamRoles[0];
            $teamRole->team_id = $request["team"];
            $teamRole->role = $request["teamRole"];

            $teamRole->save();
        }


        $user->name = $request["name"];
        $user->role_id = $request["role"];
        $user->save();

        return redirect()->route('users.show', $user->id)
            ->with('success', 'User updated successfully.');
    }

    public function editPassword(User $user)
    {
        return view('users.password_edit', compact('user'));
    }

    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ]);

        if (!Hash::check($request->input('old_password'), $user->password)) {
            throw ValidationException::withMessages([
                'old_password' => ['The provided password does not match your current password.'],
            ]);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return redirect()->route('users.show', $user->id)
            ->with('success', 'User updated successfully.');
    }

    public function profile(User $user)
    {
        return view('users.profile', compact('user'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        abort(501);
    }
}
