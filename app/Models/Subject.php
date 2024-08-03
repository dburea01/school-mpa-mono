<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasCreatedUpdatedBy;
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    // tell Eloquent that key is a string, not an integer
    protected $keyType = 'string';

    protected $fillable = [
        'short_name',
        'name',
        'position',
        'comment',
        'is_active',
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
