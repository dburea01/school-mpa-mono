<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $is_absent
 */
class Result extends Model
{
    use HasCreatedUpdatedBy, HasFactory;

    protected $fillable = [
        'user_id',
        'work_id',
        'note',
        'appreciation_id',
        'comment',
        'is_absent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    }

    public function appreciation(): BelongsTo
    {
        return $this->belongsTo(Appreciation::class);
    }
}
