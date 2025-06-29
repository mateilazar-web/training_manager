<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $link
 * @property string $video
 * @property SessionDrill[] $sessionDrills
 * @property DrillTag[] $drillTags
 */
class Drill extends Model
{
    /**
     * @var array<int,string>
     */
    protected $fillable = ['name', 'description', 'created_at', 'updated_at', 'link', 'video'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sessionDrills()
    {
        return $this->hasMany('App\Models\SessionDrill');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function drillTags()
    {
        return $this->hasMany('App\Models\DrillTag');
    }
}
