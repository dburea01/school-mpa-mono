<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Work extends Model
{
    use HasFactory;
    use HasCreatedUpdatedBy;
    use HasUuids;

    protected $fillable = [
        'id',
        'work_type_id',
        'subject_id',
        'classroom_id',
        'work_status_id',
        'title',
        'description',
        'given_at',
        'expected_at',
        'estimated_duration',
        'instruction',
        'note_min',
        'note_max',
        'note_increment',
    ];

    public function workType(): BelongsTo
    {
        return $this->belongsTo(WorkType::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function workStatus(): BelongsTo
    {
        return $this->belongsTo(WorkStatus::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    protected function getGivenAtAttribute(?string $value): string
    {
        if (isset($value)) {
            return Carbon::createFromFormat('Y-m-d', $value) ? Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y') : '';
        } else {
            return '';
        }
    }

    protected function getExpectedAtAttribute(?string $value): string
    {
        return $this->getGivenAtAttribute($value);
    }

    protected function setGivenAtAttribute(?string $value): void
    {
        if (isset($value)) {
            $this->attributes['given_at'] = Carbon::createFromFormat('d/m/Y', $value) ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
        }
    }

    protected function setExpectedAtAttribute(?string $value): void
    {
        if (isset($value)) {
            $this->attributes['expected_at'] = Carbon::createFromFormat('d/m/Y', $value) ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
        }
    }
}
