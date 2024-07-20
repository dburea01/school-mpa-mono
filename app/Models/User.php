<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property string $id
 * @property string $full_name
 * @property string $role_id
 * @property string $email
 */
class User extends Authenticatable
{
    use HasCreatedUpdatedBy;
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'id',
        'role_id',
        'civility_id',
        'login_status_id',
        'last_name',
        'first_name',
        'birth_date',
        'gender_id',
        'email',
        'phone_number',
        'address',
        'postal_code',
        'city',
        'country_id',
        'health_comment',
        'other_comment',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    protected function setLastNameAttribute(string $value): void
    {
        $this->attributes['last_name'] = strtoupper($value);
    }

    protected function setFirstNameAttribute(string $value): void
    {
        $this->attributes['first_name'] = ucfirst($value);
    }

    public function getBirthDateAttribute(?string $value = null): ?string
    {
        /** @phpstan-ignore-next-line */
        return $value ? Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y') : null;
    }

    public function setBirthDateAttribute(?string $value = null): void
    {
        /** @phpstan-ignore-next-line */
        $this->attributes['birth_date'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }

    public function getAgeAttribute(): float
    {
        if ($this->attributes['birth_date'] != null) {
            return Carbon::parse($this->attributes['birth_date'])->age;
        } else {
            return 0;
        }
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function isAdmin(): bool
    {
        return $this->role_id == 'ADMIN';
    }
}
