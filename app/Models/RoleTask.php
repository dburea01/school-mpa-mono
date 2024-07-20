<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'task_id',
    ];
}
