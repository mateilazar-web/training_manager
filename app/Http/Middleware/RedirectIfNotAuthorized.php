<?php

namespace App\Http\Middleware;

use App\Enums\UserTeamRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAuthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            abort(403);
        }

        if (
            Auth::user()->role->name != "Admin"
        ) {
            if (strpos($request->route()->uri, "users") !== false) {
                if ($request->route('user')) {
                    if (Auth::user()->team != $request->route('user')->team) {
                        abort(403);
                    }

                    if (Auth::user()->userTeamRoles->count() > 0) {
                        if (
                            Auth::user()->userTeamRoles[0]->role != UserTeamRole::Owner->value
                            && Auth::user()->id != $request->route('user')->id
                        ) {
                            abort(403);
                        }
                    } else {
                        return redirect()->route('teams.index');
                    }

                    return $next($request);
                }
            }

            if ($request->route()->uri == "teams") {
                if (!in_array($request->route()->getName(), ["teams.show", "teams.edit", "teams.update"])) {
                    if (Auth::user()->userTeamRoles->count() > 0) {
                        return redirect()->route('teams.show', Auth::user()->userTeamRoles[0]->team);
                    }
                }
            }
        }

        return $next($request);
    }
}
