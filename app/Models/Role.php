<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    use HasCreatedUpdatedBy;

    public $incrementing = false;

    // tell Eloquent that key is a string, not an integer
    protected $keyType = 'string';

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $fillable = [
        'id',
        'name', 'comment',
        'displayable',
    ];
}
