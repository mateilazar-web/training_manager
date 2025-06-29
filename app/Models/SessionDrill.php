<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $drill_id
 * @property integer $session_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $comment
 * @property string $feedback
 * @property integer $stars
 * @property Drill $drill
 * @property Session $session
 */
class SessionDrill extends Model
{
    /**
     * @var array<int,string>
     */
    protected $fillable = ['drill_id', 'session_id', 'created_at', 'updated_at', 'comment', 'feedback', 'stars'];

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
    public function session()
    {
        return $this->belongsTo('App\Models\Session');
    }
}
