<?php

namespace App\Providers;

use App\Enums\UserTeamRole;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-admin-section', function ($user) {
            return isset($user)
                && $user->role->name === 'Admin';
        });

        Gate::define('view-user-section', function ($user) {
            return $user !== null;
        });

        Gate::define('view-team-member-section', function ($user) {
            return $user->userTeamRoles->count() > 0
                && $user->userTeamRoles[0]->role !== UserTeamRole::Pending->value;
        });

        Gate::define('view-sessions-search', function ($user) {
            return Route::currentRouteName() == 'sessions.index';
        });
    }
}
