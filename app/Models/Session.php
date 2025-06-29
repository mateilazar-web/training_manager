<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $tag_id
 * @property integer $user_id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property SessionDrill[] $sessionDrills
 * @property Tag $tag
 * @property User $user
 */
class Session extends Model
{
    /**
     * @var array<int,string>
     */
    protected $fillable = ['tag_id', 'user_id', 'name', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sessionDrills()
    {
        return $this->hasMany('App\Models\SessionDrill');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo('App\Models\Tag');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
