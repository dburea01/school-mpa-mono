<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Civility extends Model
{
    public $incrementing = false;

    // tell Eloquent that key is a string, not an integer
    protected $keyType = 'string';

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $fillable = [
        'id',
        'short_name',
        'name',
        'is_active',
        'position',
    ];
}
