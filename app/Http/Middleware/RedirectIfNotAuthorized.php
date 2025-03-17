<?php

namespace App\Http\Middleware;

use App\Enums\UserTeamRole;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
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

        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();
        
        if (
            $authenticatedUser->role->name != "Admin"
        ) {
            $currentRoute = $request->route();

            if ($currentRoute instanceof Route) {
                if (strpos($currentRoute->uri, "users") !== false) {
                    if ($request->route('user')) {
                        /** @var User $currentRouteUser */
                        $currentRouteUser = $request->route('user');

                        if (count($authenticatedUser->userTeamRoles) > 0) {
                            if ($authenticatedUser->userTeamRoles[0]->team != $currentRouteUser->userTeamRoles[0]->team) {
                                abort(403);
                            }

                            if (
                                $authenticatedUser->userTeamRoles[0]->role != UserTeamRole::Owner->value
                                && $authenticatedUser->id != $currentRouteUser->id
                            ) {
                                abort(403);
                            }
                        } else {
                            return redirect()->route('teams.index');
                        }

                        return $next($request);
                    }
                }   
            
                if ($currentRoute->uri == "teams") {
                    if (!in_array($currentRoute->getName(), ["teams.show", "teams.edit", "teams.update"])) {
                        $roles = $authenticatedUser->userTeamRoles;
    
                        if (count($roles) > 0) {
                            return redirect()->route('teams.show', $roles[0]->team);
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}
