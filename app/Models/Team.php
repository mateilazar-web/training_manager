<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property UserTeamRole[] $userTeamRoles
 * @property User $user
 */
class Team extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['created_by', 'created_at', 'updated_at', 'name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userTeamRoles()
    {
        return $this->hasMany('App\Models\UserTeamRole');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }
}
