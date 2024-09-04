<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appreciation extends Model
{
    use HasCreatedUpdatedBy, HasFactory;

    protected $fillable = [
        'position',
        'short_name',
        'name',
        'comment',
    ];

    protected function setShortNameAttribute(string $value): void
    {
        $this->attributes['short_name'] = strtoupper($value);
    }

    protected function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = ucfirst($value);
    }
}
