<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property mixed $authorization
 */
class Role extends Model
{
    /**
     * @var array<int,string>
     */
    protected $fillable = ['created_at', 'updated_at', 'name', 'authorization'];
}
