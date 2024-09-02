<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appreciation extends Model
{
    protected $fillable = [
        'position',
        'short_name',
        'name',
        'comment',
    ];
}
