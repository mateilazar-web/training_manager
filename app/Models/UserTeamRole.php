<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $team_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $role
 * @property Team $team
 * @property User $user
 */
class UserTeamRole extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'team_id', 'created_at', 'updated_at', 'role'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
