<?php

namespace App\Models;

use App\Enums\UserTeamRole;
use App\Models\UserTeamRole as ModelsUserTeamRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property integer $id
 * @property integer $role_id
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property Session[] $sessions
 * @property Role $role
 * @property ModelsUserTeamRole[] $userTeamRoles
 */
class User extends Authenticatable
{
    /**
     * @var array
     */
    protected $fillable = ['role_id', 'name', 'email', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sessions()
    {
        return $this->hasMany('App\Models\Session');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userTeamRoles()
    {
        return $this->hasMany('App\Models\UserTeamRole');
    }

    public function scopeVisibleTo(Builder $query, User $user)
    {
        // If you are the owner or the coach of the team,
        // you can see all users in that team
        if (count($user->userTeamRoles) > 0) {
            $team = $user->userTeamRoles[0]->team;
            $teamRole = $user->userTeamRoles[0]->role;
            if (
                $teamRole === UserTeamRole::Owner->value
                || $teamRole === UserTeamRole::Coach->value
            ) {
                return $query->whereHas('userTeamRoles', function ($query) use ($team) {
                    $query->where('team_id', $team->id);
                });
            }
        }

        // If you are pending user of the team, you can see only yourself
        return $query->where('user_id', $user->id);
    }
}
