<?php

namespace App\Policies;

use App\Enums\UserTeamRole;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if a user can view another user.
     */
    public function view(User $authUser, User $user)
    {
        return $authUser->userTeamRoles[0]->role === UserTeamRole::Owner->value || $authUser->id === $user->id;
    }

    public function updateField(User $user, User $record, $field)
    {
        // Define editable fields based on user role or logic
        $editableFields = $user === $record || $user->role->name === 'Admin'
            ? ['name', 'email', 'team', 'team_role'] // Admin can edit these fields
            : [];          // Regular users can only edit these fields

        if (
            count($user->userTeamRoles) > 0
            && $user->userTeamRoles[0]->role === UserTeamRole::Owner->value
        ) {
            $editableFields[] = 'team_role';
        }

        return in_array($field, $editableFields);
    }
}
