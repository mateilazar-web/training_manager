<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $drill_id
 * @property integer $tag_id
 * @property string $created_at
 * @property string $updated_at
 * @property Drill $drill
 * @property Tag $tag
 */
class DrillTag extends Model
{
    /**
     * @var array<int,string>
     */
    protected $fillable = ['drill_id', 'tag_id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function drill()
    {
        return $this->belongsTo('App\Models\Drill');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo('App\Models\Tag');
    }
}
