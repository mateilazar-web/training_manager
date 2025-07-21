<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property DrillTag[] $drillTags
 * @property Session[] $sessions
 * @property SessionDrill[] $sessionDrills
 */
class Tag extends Model
{
    /**
     * @var array<int,string>
     */
    protected $fillable = ['name', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function drillTags()
    {
        return $this->hasMany('App\Models\DrillTag');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sessions()
    {
        return $this->hasMany('App\Models\Session');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sessionDrills()
    {
        return $this->hasMany('App\Models\SessionDrill', 'session_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }
}
