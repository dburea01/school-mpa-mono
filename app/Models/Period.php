<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Period extends Model
{
    use HasCreatedUpdatedBy, HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_current',
        'comment',
    ];

    protected function getStartDateAttribute(?string $value): string
    {
        if (isset($value)) {
            return Carbon::createFromFormat('Y-m-d', $value) ? Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y') : '';
        } else {
            return '';
        }
    }

    protected function getEndDateAttribute(?string $value): string
    {
        return $this->getStartDateAttribute($value);
    }

    protected function setStartDateAttribute(?string $value): void
    {
        if (isset($value)) {
            $this->attributes['start_date'] = Carbon::createFromFormat('d/m/Y', $value) ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
        }
    }

    protected function setEndDateAttribute(?string $value): void
    {
        if (isset($value)) {
            $this->attributes['end_date'] = Carbon::createFromFormat('d/m/Y', $value) ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
        }
    }

    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class);
    }

    /*
    public function assignments(): HasManyThrough
    {
        return $this->hasManyThrough(Assignment::class, Classroom::class);
    }
        */

    public function works(): HasManyThrough
    {
        return $this->hasManyThrough(Work::class, Classroom::class);
    }
}
