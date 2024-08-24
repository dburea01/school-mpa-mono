<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkType extends Model
{
    use HasCreatedUpdatedBy, HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'short_name',
        'name',
        'comment',
        'is_active',
    ];

    public function setShortNameAttribute(string $value): void
    {
        $this->attributes['short_name'] = strtoupper($value);
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = ucfirst($value);
    }
}
