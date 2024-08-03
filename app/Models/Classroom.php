<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Classroom extends Model
{
    use HasCreatedUpdatedBy;
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    // tell Eloquent that key is a string, not an integer
    protected $keyType = 'string';

    protected $fillable = [
        'period_id',
        'short_name',
        'name',
        'comment',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }
}
