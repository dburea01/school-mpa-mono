<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    use HasCreatedUpdatedBy;
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    // tell Eloquent that key is a string, not an integer
    protected $keyType = 'string';

    protected $fillable = [
        'classroom_id',
        'user_id',
        'subject_id',
        'start_date',
        'end_date',
        'is_main_teacher',
        'comment',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class)->where('role_id', 'STUDENT');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class)->where('role_id', 'TEACHER');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
}
